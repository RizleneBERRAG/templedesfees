<?php

namespace App\Filament\Resources\Reviews\Tables;

use App\Models\Review;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // Les avis en attente d'abord, puis du plus recent au plus ancien — le
            // meme ordre que sur le site. defaultSort() n'accepte qu'un critere,
            // d'ou le tri porte sur la requete.
            ->modifyQueryUsing(fn (Builder $query) => $query->orderBy('est_publie')->orderByDesc('publie_le'))
            ->columns([
                TextColumn::make('prenom')
                    ->label('Prénom')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('note')
                    ->label('Note')
                    ->state(fn (Review $avis) => $avis->etoiles()),

                TextColumn::make('texte')
                    ->label('Avis')
                    ->limit(70)
                    ->wrap()
                    ->searchable(),

                TextColumn::make('publie_le')
                    ->label('Date')
                    ->date('d/m/Y')
                    ->placeholder('—')
                    ->sortable(),

                // Un avis depose par un visiteur et non publie n'est pas « masque » :
                // il attend une relecture. La nuance change ce qu'on en fait.
                TextColumn::make('est_publie')
                    ->label('État')
                    ->badge()
                    ->state(fn (Review $avis) => match (true) {
                        $avis->est_publie           => 'Affiché',
                        $avis->source === 'site'    => 'En attente',
                        default                     => 'Masqué',
                    })
                    ->color(fn (Review $avis) => match (true) {
                        $avis->est_publie        => 'success',
                        $avis->source === 'site' => 'warning',
                        default                  => 'gray',
                    })
                    ->description(fn (Review $avis) => $avis->source === 'site' ? 'déposé sur le site' : null),
            ])
            ->filters([
                TernaryFilter::make('est_publie')
                    ->label('Affichage')
                    ->placeholder('Tous')
                    ->trueLabel('Affichés')
                    ->falseLabel('Masqués'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
