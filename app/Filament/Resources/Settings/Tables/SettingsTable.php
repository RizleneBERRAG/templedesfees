<?php

namespace App\Filament\Resources\Settings\Tables;

use App\Models\Setting;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SettingsTable
{
    /** Les groupes du seeder, dans l'ordre ou ils comptent. */
    private const GROUPES = [
        'legal'   => 'Mentions légales',
        'contact' => 'Contact',
        'elevage' => 'L’élevage',
        'general' => 'Général',
    ];

    public static function configure(Table $table): Table
    {
        return $table
            // Les mentions obligatoires encore vides remontent en tete : ce sont
            // elles qui font afficher « à compléter » sur la page légale.
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->orderByRaw('CASE WHEN est_obligatoire = 1 AND (valeur IS NULL OR valeur = ?) THEN 0 ELSE 1 END', [''])
                ->orderBy('groupe')
                ->orderBy('cle'))
            ->columns([
                TextColumn::make('libelle')
                    ->label('Réglage')
                    ->description(fn (Setting $r) => $r->cle)
                    ->searchable(['libelle', 'cle'])
                    ->wrap(),

                TextColumn::make('valeur')
                    ->label('Valeur')
                    ->placeholder('à compléter')
                    ->color(fn (Setting $r) => blank($r->valeur) && $r->est_obligatoire ? 'warning' : null)
                    ->limit(60)
                    ->wrap()
                    ->searchable(),

                TextColumn::make('groupe')
                    ->label('Rubrique')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => self::GROUPES[$state] ?? $state),

                TextColumn::make('est_obligatoire')
                    ->label('')
                    ->badge()
                    ->state(fn (Setting $r) => $r->est_obligatoire ? 'Obligatoire' : '')
                    ->color('danger'),
            ])
            ->filters([
                SelectFilter::make('groupe')
                    ->label('Rubrique')
                    ->options(self::GROUPES),
            ])
            ->recordActions([EditAction::make()->label('Modifier')])
            // Ni creation ni suppression : le code lit ces cles par leur nom.
            ->toolbarActions([]);
    }
}
