<?php

namespace App\Filament\Resources\Reservations\Schemas;

use App\Enums\KittenStatus;
use App\Models\AdoptionRequest;
use App\Models\Kitten;
use App\Models\Reservation;
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
                ])
                ->columns(2),

            Section::make('L’acompte')
                ->schema([

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
                        ->dehydrateStateUsing(fn ($state) => (int) round(((float) str_replace(',', '.', (string) $state)) * 100))
                        ->helperText('Déduit du prix du chaton au moment du départ.'),

                    DatePicker::make('expire_le')
                        ->label('À régler avant le')
                        ->native(false)
                        ->displayFormat('j F Y')
                        ->default(fn () => now()->addDays(config('chatterie.paiement.delai_jours'))->endOfDay())
                        ->helperText('Passé ce délai, le chaton est automatiquement remis en vente.'),

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
