<?php

namespace App\Filament\Resources\Litters\Tables;

use App\Models\Litter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LittersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['pere', 'mere'])->withCount('kittens'))
            ->defaultSort('date_naissance', 'desc')
            ->columns([
                TextColumn::make('code')
                    ->label('Portée')
                    ->description(fn (Litter $portee) => trim(($portee->pere?->nom ?? '?').' × '.($portee->mere?->nom ?? '?')))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('date_naissance')
                    ->label('Naissance')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('date_disponibilite')
                    ->label('Départs dès le')
                    ->date('d/m/Y')
                    ->placeholder('—')
                    ->sortable(),

                TextColumn::make('kittens_count')
                    ->label('Chatons')
                    ->badge(),

                TextColumn::make('loof_portee_numero')
                    ->label('N° de portée LOOF')
                    ->placeholder('à renseigner')
                    ->fontFamily('mono')
                    ->searchable()
                    // Le numero manquant bloque toutes les fiches chaton de la portee :
                    // il doit sauter aux yeux dans la liste.
                    ->color(fn (?string $state) => blank($state) ? 'warning' : null)
                    ->description(fn (Litter $portee) => blank($portee->loof_portee_numero) && $portee->kittens_count > 0
                        ? "bloque {$portee->kittens_count} fiche(s) chaton"
                        : null),

                TextColumn::make('est_publiee')
                    ->label('Portée')
                    ->badge()
                    ->state(fn (Litter $portee) => $portee->est_publiee ? 'Affichée' : 'Masquée')
                    ->color(fn (Litter $portee) => $portee->est_publiee ? 'success' : 'gray'),
            ])
            ->filters([
                TernaryFilter::make('est_publiee')
                    ->label('Affichage')
                    ->placeholder('Toutes')
                    ->trueLabel('Affichées')
                    ->falseLabel('Masquées'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
