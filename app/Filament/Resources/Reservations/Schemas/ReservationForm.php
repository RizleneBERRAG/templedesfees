<?php

namespace App\Filament\Resources\Reservations\Schemas;

use App\Enums\KittenStatus;
use App\Models\AdoptionRequest;
use App\Models\Kitten;
use App\Models\Reservation;
use App\Support\Monnaie;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class ReservationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Le chaton et la famille')
                ->description('Une réservation se crée après la visite, pour une famille que vous avez rencontrée.')
                ->schema([

                    Select::make('kitten_id')
                        ->label('Chaton')
                        ->required()
                        ->searchable()
                        ->preload()
                        /*
                         * Seuls les chatons encore proposables, plus celui deja
                         * choisi quand on revient sur une fiche : sans cette
                         * exception, rouvrir une reservation payee viderait le
                         * champ et perdrait le lien.
                         */
                        ->options(fn (?Reservation $record) => Kitten::query()
                            ->where(fn ($q) => $q
                                ->where('statut', KittenStatus::Disponible)
                                ->when($record?->kitten_id, fn ($q, $id) => $q->orWhere('id', $id)))
                            ->with('litter')
                            ->get()
                            ->mapWithKeys(fn (Kitten $c) => [
                                $c->id => $c->nom.' — '.$c->robe.' ('.$c->statut->libelle().')',
                            ]))
                        /*
                         * Le prix et la date de depart suivent le chaton : ils
                         * sont deja sur sa fiche et sur celle de sa portee.
                         * On ne les repose que s'ils sont vides — sur une fiche
                         * qu'on rouvre, ce qui a ete convenu l'emporte sur ce
                         * que dit le tarif du jour.
                         */
                        ->live()
                        ->afterStateUpdated(function ($state, Get $get, Set $set) {
                            $chaton = Kitten::with('litter')->find($state);

                            if ($chaton?->prix_centimes && blank($get('prix_centimes'))) {
                                $set('prix_centimes', $chaton->prix_centimes / 100);
                            }

                            if ($chaton?->litter?->date_disponibilite && blank($get('depart_prevu_le'))) {
                                $set('depart_prevu_le', $chaton->litter->date_disponibilite);
                            }
                        })
                        ->helperText('Le chaton n’est bloqué qu’une fois l’acompte encaissé, pas à la création.'),

                    Select::make('adoption_request_id')
                        ->label('Dossier d’adoption')
                        ->searchable()
                        ->preload()
                        ->options(fn () => AdoptionRequest::query()
                            ->orderByDesc('created_at')
                            ->limit(100)
                            ->get()
                            ->mapWithKeys(fn (AdoptionRequest $d) => [
                                $d->id => trim("$d->prenom $d->nom").' · '.$d->email,
                            ]))
                        /*
                         * Choisir un dossier remplit la famille : c'est la
                         * seule chose qu'on retape a chaque fois, et c'est la
                         * seule qu'on peut se tromper en retapant.
                         */
                        ->afterStateUpdated(function ($state, Set $set) {
                            $dossier = AdoptionRequest::find($state);

                            if (! $dossier) {
                                return;
                            }

                            $set('prenom', $dossier->prenom);
                            $set('nom', $dossier->nom);
                            $set('email', $dossier->email);
                            $set('telephone', $dossier->telephone);
                            $set('code_postal', $dossier->code_postal);
                        })
                        ->live()
                        ->helperText('Facultatif. Le choisir remplit les coordonnées ci-dessous.'),

                    TextInput::make('prenom')->label('Prénom')->required()->maxLength(80),
                    TextInput::make('nom')->label('Nom')->required()->maxLength(80),
                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->maxLength(180)
                        ->helperText('C’est à cette adresse que part le lien de paiement.'),
                    TextInput::make('telephone')->label('Téléphone')->tel()->maxLength(30),

                    /*
                     * L'adresse ne sert pas au paiement : elle sert au contrat,
                     * qui identifie ses parties. Une reservation sans adresse
                     * marche, son contrat est incomplet — le document le
                     * signale a l'ecran plutot que de le taire.
                     */
                    TextInput::make('adresse')
                        ->label('Adresse')
                        ->maxLength(180)
                        ->columnSpanFull()
                        ->helperText('Portée au contrat de réservation. Jamais affichée sur le site.'),

                    TextInput::make('code_postal')->label('Code postal')->maxLength(10),
                    TextInput::make('ville')->label('Ville')->maxLength(80),
                ])
                ->columns(2),

            Section::make('Le prix et l’acompte')
                ->description('Ces montants sont écrits au contrat et à la facture. Le prix n’apparaît sur aucune page du site.')
                ->schema([

                    /*
                     * Le prix est recopie depuis la fiche du chaton a la
                     * creation, puis fige : un tarif peut changer d'une portee
                     * a l'autre, un contrat deja signe ne change pas.
                     */
                    TextInput::make('prix_centimes')
                        ->label('Prix convenu du chaton')
                        ->numeric()
                        ->suffix('€')
                        ->formatStateUsing(fn (?int $state) => $state === null ? null : $state / 100)
                        ->dehydrateStateUsing(fn ($state) => filled($state) ? Monnaie::centimes($state) : null)
                        ->helperText('Sert à calculer le solde restant dû au départ.'),

                    /*
                     * Le montant est stocke en centimes et saisi en euros. La
                     * conversion se fait ici, aux deux bouts : une somme en
                     * flottant finit toujours par produire un 199,99 la ou on
                     * avait tape 200.
                     */
                    TextInput::make('acompte_centimes')
                        ->label('Montant de l’acompte')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->suffix('€')
                        ->default(fn () => config('chatterie.paiement.acompte_defaut_centimes') / 100)
                        ->formatStateUsing(fn (?int $state) => $state === null ? null : $state / 100)
                        ->dehydrateStateUsing(fn ($state) => Monnaie::centimes($state))
                        ->helperText('Déduit du prix du chaton au moment du départ.'),

                    DatePicker::make('expire_le')
                        ->label('À régler avant le')
                        ->native(false)
                        ->displayFormat('j F Y')
                        ->default(fn () => now()->addDays(config('chatterie.paiement.delai_jours'))->endOfDay())
                        ->helperText('Passé ce délai, le chaton est automatiquement remis en vente.'),

                    DatePicker::make('depart_prevu_le')
                        ->label('Départ prévu le')
                        ->native(false)
                        ->displayFormat('j F Y')
                        ->helperText('Douze semaines révolues au plus tôt. Écrit au contrat ; vide, c’est la date de la portée qui sert.'),

                    Placeholder::make('statut_affiche')
                        ->label('Statut')
                        ->content(fn (?Reservation $record) => $record?->statut->libelle() ?? 'En attente de l’acompte')
                        /*
                         * Le statut ne se change pas a la main : le passer de
                         * force laisserait le chaton dans un etat qui ne
                         * correspond a rien. Les boutons en haut de fiche
                         * s'occupent du chaton en meme temps.
                         */
                        ->helperText('Se met à jour tout seul. Les boutons en haut de la fiche servent à annuler ou rembourser.'),

                    Placeholder::make('lien')
                        ->label('Lien de paiement')
                        ->visible(fn (?Reservation $record) => $record !== null)
                        ->content(fn (?Reservation $record) => $record?->lienPublic())
                        ->helperText('À envoyer à la famille. Il ne demande aucun compte et reste valable jusqu’à la date ci-dessus.'),
                ])
                ->columns(2),

            Section::make('Notes')
                ->collapsed()
                ->schema([
                    Textarea::make('note_interne')
                        ->label('Note interne')
                        ->rows(3)
                        ->helperText('Jamais affichée sur le site.'),
                ]),
        ]);
    }
}
