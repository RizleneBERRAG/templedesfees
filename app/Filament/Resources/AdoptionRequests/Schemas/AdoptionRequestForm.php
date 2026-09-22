<?php

namespace App\Filament\Resources\AdoptionRequests\Schemas;

use App\Models\Kitten;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AdoptionRequestForm
{
    /** Les libelles des statuts, dans l'ordre du parcours d'adoption. */
    public const STATUTS = [
        'nouveau'       => 'Nouvelle demande',
        'en_cours'      => 'Échange en cours',
        'visite_prevue' => 'Visite prévue',
        'accepte'       => 'Acceptée',
        'refuse'        => 'Refusée',
        'archive'       => 'Archivée',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                /*
                 * Ce que la famille a ecrit est en lecture seule. On ne corrige pas
                 * les mots de quelqu'un dans son dossier : seuls le statut et la
                 * note interne appartiennent a l'elevage.
                 */
                Section::make('La famille')
                    ->description('Renseigné par la famille depuis le site. Non modifiable.')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('identite')->label('Nom')
                            ->content(fn ($record) => trim($record?->prenom.' '.$record?->nom)),
                        Placeholder::make('email')->label('Email')
                            ->content(fn ($record) => $record?->email),
                        Placeholder::make('telephone')->label('Téléphone')
                            ->content(fn ($record) => $record?->telephone ?: '—'),
                        Placeholder::make('code_postal')->label('Code postal')
                            ->content(fn ($record) => $record?->code_postal ?: '—'),
                    ]),

                Section::make('Le projet')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('chaton')->label('Chaton souhaité')
                            ->content(fn ($record) => $record?->kitten_id
                                ? (Kitten::find($record->kitten_id)?->nom ?? 'fiche supprimée')
                                : ($record?->souhait ?: 'Aucun chaton en particulier')),
                        Placeholder::make('logement')->label('Logement')
                            ->content(fn ($record) => $record?->logement ?: '—'),
                        Placeholder::make('autres_animaux')->label('Autres animaux')
                            ->content(fn ($record) => $record?->autres_animaux ?: '—'),
                        Placeholder::make('presence')->label('Présence au foyer')
                            ->content(fn ($record) => $record?->presence ?: '—'),
                        Placeholder::make('experience')->label('Expérience')
                            ->content(fn ($record) => $record?->experience ?: '—'),
                        Placeholder::make('message')->label('Message')
                            ->content(fn ($record) => $record?->message ?: '—')
                            ->columnSpanFull(),
                    ]),

                Section::make('Suivi de l’élevage')
                    ->columns(1)
                    ->schema([
                        Select::make('statut')
                            ->label('Où en est ce dossier')
                            ->options(self::STATUTS)
                            ->default('nouveau')
                            ->required(),

                        Textarea::make('note_interne')
                            ->label('Note interne')
                            ->helperText('Visible uniquement ici, jamais sur le site.')
                            ->rows(4)
                            ->maxLength(2000),
                    ]),

                Section::make('Données personnelles')
                    ->description("Le consentement est horodaté au dépôt, et le dossier est supprimé automatiquement à la date de purge par la commande planifiée rgpd:purge.")
                    ->columns(2)
                    ->schema([
                        Placeholder::make('consentement_le')->label('Consentement recueilli le')
                            ->content(fn ($record) => $record?->consentement_le?->translatedFormat('j F Y à H:i') ?: '—'),
                        Placeholder::make('a_purger_le')->label('Suppression automatique le')
                            ->content(fn ($record) => $record?->a_purger_le?->translatedFormat('j F Y') ?: '—'),
                    ]),
            ]);
    }
}
