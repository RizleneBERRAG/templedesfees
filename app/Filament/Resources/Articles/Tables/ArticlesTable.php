<?php

namespace App\Filament\Resources\Articles\Tables;

use App\Models\Article;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date_publication', 'desc')
            ->columns([
                ImageColumn::make('photo_principale')
                    ->label('')
                    ->disk('site')
                    ->height(46),

                TextColumn::make('titre')
                    ->label('Titre')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->description(fn (Article $a) => $a->categorie),

                TextColumn::make('date_publication')
                    ->label('Publication')
                    ->date('d/m/Y')
                    ->sortable(),

                // Trois états et non deux : « publié » coché ne suffit pas si la
                // date est encore à venir. La colonne dit ce que voit le
                // visiteur, pas ce que dit la case.
                TextColumn::make('etat')
                    ->label('Sur le site')
                    ->badge()
                    ->state(fn (Article $a) => match (true) {
                        ! $a->est_publie                  => 'Brouillon',
                        $a->date_publication->isFuture()  => 'Programmé',
                        default                           => 'En ligne',
                    })
                    ->color(fn (string $state) => match ($state) {
                        'En ligne'   => 'success',
                        'Programmé'  => 'info',
                        default      => 'warning',
                    }),

                TextColumn::make('lecture')
                    ->label('Lecture')
                    ->state(fn (Article $a) => $a->minutesDeLecture().' min')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('est_publie')
                    ->label('Publié')
                    ->placeholder('Tous')
                    ->trueLabel('Publiés')
                    ->falseLabel('Brouillons'),

                SelectFilter::make('categorie')
                    ->label('Rubrique')
                    ->options(fn () => Article::query()
                        ->whereNotNull('categorie')
                        ->distinct()
                        ->pluck('categorie', 'categorie')
                        ->all()),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Aucun article pour l’instant')
            ->emptyStateDescription('La rubrique Articles n’apparaît sur le site qu’une fois le premier article publié.');
    }
}
