<?php

namespace App\Filament\Resources\Photos\Tables;

use App\Models\Photo;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PhotosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('attachable'))
            ->defaultSort('ordre')
            ->columns([
                ImageColumn::make('chemin')
                    ->label('')
                    ->disk('site')
                    ->height(56),

                TextColumn::make('alt')
                    ->label('Texte alternatif')
                    ->placeholder('manquant')
                    ->color(fn (?string $state) => blank($state) ? 'danger' : null)
                    ->wrap()
                    ->searchable(),

                TextColumn::make('categorie')
                    ->label('Catégorie')
                    ->badge()
                    ->placeholder('—'),

                TextColumn::make('chemin')
                    ->label('Fichier')
                    ->formatStateUsing(fn (string $state) => basename($state))
                    ->fontFamily('mono')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('ordre')
                    ->label('Ordre')
                    ->sortable(),

                TextColumn::make('est_publiee')
                    ->label('État')
                    ->badge()
                    ->state(fn (Photo $photo) => $photo->est_publiee ? 'Visible' : 'Masquée')
                    ->color(fn (Photo $photo) => $photo->est_publiee ? 'success' : 'gray'),
            ])
            ->filters([
                SelectFilter::make('categorie')
                    ->label('Catégorie')
                    ->options([
                        'chatons' => 'Chatons',
                        'adultes' => 'Adultes',
                        'maison'  => 'La maison',
                    ]),

                TernaryFilter::make('est_publiee')
                    ->label('Affichage')
                    ->placeholder('Toutes')
                    ->trueLabel('Visibles')
                    ->falseLabel('Masquées'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
