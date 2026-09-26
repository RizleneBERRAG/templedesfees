<?php

namespace App\Filament\Resources\Faqs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class FaqsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('ordre')
            ->reorderable('ordre')
            ->columns([
                TextColumn::make('question')
                    ->label('Question')
                    ->wrap()
                    ->searchable(),

                TextColumn::make('reponse')
                    ->label('Réponse')
                    ->limit(70)
                    ->toggleable(),

                IconColumn::make('est_publiee')
                    ->label('Publiée')
                    ->boolean(),
            ])
            ->filters([
                TernaryFilter::make('est_publiee')
                    ->label('Publication')
                    ->placeholder('Toutes')
                    ->trueLabel('Publiées')
                    ->falseLabel('Masquées'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
