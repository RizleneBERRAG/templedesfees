<?php

namespace App\Filament\Resources\Cats\Tables;

use App\Enums\CatRole;
use App\Models\Cat;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CatsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->orderBy('ordre')->orderBy('nom'))
            ->columns([
                ImageColumn::make('photo_principale')
                    ->label('')
                    ->disk('site')
                    ->height(52),

                TextColumn::make('nom')
                    ->label('Nom')
                    ->description(fn (Cat $chat) => $chat->robe)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role')
                    ->label('Rôle')
                    ->badge()
                    ->sortable(),

                TextColumn::make('sexe')
                    ->label('Sexe')
                    ->formatStateUsing(fn (string $state) => $state === 'male' ? 'Mâle' : 'Femelle'),

                TextColumn::make('annee_naissance')
                    ->label('Née en')
                    ->placeholder('—')
                    ->sortable(),

                TextColumn::make('loof_numero')
                    ->label('LOOF')
                    ->placeholder('à renseigner')
                    ->fontFamily('mono')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('est_publie')
                    ->label('État')
                    ->badge()
                    ->state(fn (Cat $chat) => $chat->est_publie ? 'En ligne' : 'Masquée')
                    ->color(fn (Cat $chat) => $chat->est_publie ? 'success' : 'gray'),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Rôle')
                    ->options(CatRole::class),

                TernaryFilter::make('est_publie')
                    ->label('Affichage')
                    ->placeholder('Toutes')
                    ->trueLabel('En ligne')
                    ->falseLabel('Masquées'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
