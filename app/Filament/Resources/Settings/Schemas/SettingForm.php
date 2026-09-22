<?php

namespace App\Filament\Resources\Settings\Schemas;

use App\Models\Setting;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Réglage')
                    ->columns(1)
                    ->schema([
                        /*
                         * La cle et le libelle sont poses par le seeder : le code les
                         * lit par leur nom. Les renommer ici casserait silencieusement
                         * la page qui les affiche, d'ou la lecture seule.
                         */
                        Placeholder::make('libelle')->label('Réglage')
                            ->content(fn (?Setting $record) => $record?->libelle ?? $record?->cle),

                        Placeholder::make('cle')->label('Identifiant technique')
                            ->content(fn (?Setting $record) => $record?->cle),

                        Textarea::make('valeur')
                            ->label('Valeur')
                            ->rows(2)
                            ->autosize()
                            ->helperText(fn (?Setting $record) => $record?->est_obligatoire
                                ? 'Mention obligatoire : tant qu’elle est vide, le site affiche « à compléter » à sa place.'
                                : 'Laisser vide masque l’information sur le site.'),
                    ]),
            ]);
    }
}
