<?php

namespace App\Filament\Resources\AdoptionRequests\Actions;

use App\Enums\KittenStatus;
use App\Filament\Resources\Reservations\ReservationResource;
use App\Models\AdoptionRequest;
use App\Models\Kitten;
use App\Models\Reservation;
use App\Support\Monnaie;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Icons\Heroicon;

/**
 * Le passage du dossier a la reservation.
 *
 * C'est le moment charniere du parcours, et il se produit apres un coup de
 * telephone : la famille a rempli le formulaire de pre-adoption, l'eleveuse a
 * appele, elles se sont vues. La reservation nait de cette decision-la, pas
 * d'un panier rempli par un inconnu.
 *
 * Tout ce que le dossier sait deja est repris sans etre retape — le nom, le
 * courriel, le telephone, le chaton souhaite. Ce qui manque au contrat est
 * demande ici, une bonne fois : l'adresse postale, le prix convenu, l'acompte,
 * les deux dates. De cette seule saisie sortent le contrat de reservation, le
 * lien de paiement et, plus tard, la facture — tous les trois d'accord entre
 * eux parce qu'ils lisent la meme ligne.
 */
class PreparerLaReservation
{
    public static function make(): Action
    {
        return Action::make('preparer_reservation')
            ->label('Préparer la réservation')
            ->icon(Heroicon::OutlinedBanknotes)
            ->color('success')
            ->modalHeading('Préparer la réservation')
            ->modalDescription('Après la visite. Le contrat, le lien de paiement et la facture sortiront de ce qui est saisi ici — rien ne sera à retaper.')
            ->modalSubmitActionLabel('Créer la réservation')
            ->modalWidth('2xl')

            /*
             * Un dossier qui a deja sa reservation ne doit pas pouvoir en
             * fabriquer une seconde : deux acomptes sur le meme chaton, ce
             * sont deux familles a qui on a promis la meme chose.
             */
            ->visible(fn (AdoptionRequest $record) => $record->reservationEnCours() === null)

            /*
             * Une action ne sait pas se mettre en colonnes toute seule : c'est
             * la grille qui les pose, et les champs qui prennent toute la
             * largeur le disent eux-memes.
             */
            ->schema([
                Grid::make(2)->schema([

                    Select::make('kitten_id')
                        ->label('Chaton réservé')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->live()
                        ->default(fn (AdoptionRequest $record) => $record->kitten_id)
                        ->options(fn (AdoptionRequest $record) => Kitten::query()
                            ->where(fn ($q) => $q
                                ->where('statut', KittenStatus::Disponible)
                                ->when($record->kitten_id, fn ($q, $id) => $q->orWhere('id', $id)))
                            ->orderBy('nom')
                            ->get()
                            ->mapWithKeys(fn (Kitten $c) => [
                                $c->id => $c->nom.($c->robe ? ' — '.$c->robe : '').' ('.$c->statut->libelle().')',
                            ]))
                        // Le prix et la date de depart suivent le chaton : ils sont
                        // sur sa fiche et sur celle de sa portee, autant les y lire.
                        ->afterStateUpdated(function ($state, Set $set) {
                            $chaton = Kitten::with('litter')->find($state);

                            if ($chaton?->prix_centimes) {
                                $set('prix', $chaton->prix_centimes / 100);
                            }

                            if ($chaton?->litter?->date_disponibilite) {
                                $set('depart_prevu_le', $chaton->litter->date_disponibilite);
                            }
                        })
                        ->helperText('Le chaton ne sera bloqué qu’une fois l’acompte encaissé.')
                        ->columnSpanFull(),

                    TextInput::make('adresse')
                        ->label('Adresse')
                        ->maxLength(180)
                        ->placeholder('12 rue des Lilas')
                        ->helperText('Un contrat identifie ses parties : sans adresse, il reste incomplet.')
                        ->columnSpanFull(),

                    TextInput::make('code_postal')
                        ->label('Code postal')
                        ->maxLength(10)
                        ->default(fn (AdoptionRequest $record) => $record->code_postal),

                    TextInput::make('ville')->label('Ville')->maxLength(80),

                    TextInput::make('prix')
                        ->label('Prix convenu du chaton')
                        ->numeric()
                        ->suffix('€')
                        ->default(fn (AdoptionRequest $record) => $record->kitten?->prix_centimes
                            ? $record->kitten->prix_centimes / 100
                            : null)
                        ->helperText('Sert au contrat et au calcul du solde. Jamais affiché sur le site.'),

                    TextInput::make('acompte')
                        ->label('Acompte')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->suffix('€')
                        ->default(fn () => config('chatterie.paiement.acompte_defaut_centimes') / 100),

                    DatePicker::make('expire_le')
                        ->label('Acompte à régler avant le')
                        ->native(false)
                        ->displayFormat('j F Y')
                        ->default(fn () => now()->addDays(config('chatterie.paiement.delai_jours')))
                        ->helperText('Passé ce délai, le chaton est automatiquement remis en vente.'),

                    DatePicker::make('depart_prevu_le')
                        ->label('Départ prévu le')
                        ->native(false)
                        ->displayFormat('j F Y')
                        ->default(fn (AdoptionRequest $record) => $record->kitten?->litter?->date_disponibilite)
                        ->helperText('Douze semaines révolues au plus tôt. Écrit au contrat.'),

                ]),
            ])

            ->action(function (AdoptionRequest $record, array $data) {
                $reservation = Reservation::create([
                    'kitten_id'           => $data['kitten_id'],
                    'adoption_request_id' => $record->id,
                    'prenom'              => $record->prenom,
                    'nom'                 => $record->nom ?: '',
                    'email'               => $record->email,
                    'telephone'           => $record->telephone,
                    'adresse'             => $data['adresse'] ?? null,
                    'code_postal'         => $data['code_postal'] ?? null,
                    'ville'               => $data['ville'] ?? null,
                    'prix_centimes'       => filled($data['prix'] ?? null) ? Monnaie::centimes($data['prix']) : null,
                    'acompte_centimes'    => Monnaie::centimes($data['acompte']),
                    'expire_le'           => $data['expire_le'] ?? null,
                    'depart_prevu_le'     => $data['depart_prevu_le'] ?? null,
                ]);

                // Le dossier a abouti : il change d'etat tout seul, sinon
                // personne n'y pense et la liste des demandes ne veut plus rien
                // dire au bout de trois mois.
                $record->forceFill(['statut' => 'accepte'])->save();

                Notification::make()
                    ->title('Réservation créée.')
                    ->body('Le contrat est prêt. Copiez le lien de paiement et envoyez-le à la famille.')
                    ->success()
                    ->send();

                return redirect(ReservationResource::getUrl('edit', ['record' => $reservation]));
            });
    }
}
