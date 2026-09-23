<?php

namespace App\Filament\Resources\Reservations\Tables;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReservationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            /*
             * Ce qui attend d'abord, le reste ensuite, et du plus recent au
             * plus ancien dans chaque cas. Une eleveuse qui ouvre cet ecran
             * cherche ce qui n'est pas paye, pas l'historique.
             */
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->orderByRaw("CASE WHEN statut = 'en_attente' THEN 0 ELSE 1 END")
                ->orderByDesc('created_at'))

            ->columns([
                TextColumn::make('kitten.nom')
                    ->label('Chaton')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('nom')
                    ->label('Famille')
                    ->state(fn (Reservation $r) => $r->nomComplet())
                    ->description(fn (Reservation $r) => $r->email)
                    ->searchable(['nom', 'prenom', 'email']),

                TextColumn::make('acompte_centimes')
                    ->label('Acompte')
                    ->state(fn (Reservation $r) => $r->acompteFormate())
                    ->sortable(),

                TextColumn::make('statut')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn (ReservationStatus $state) => $state->libelle())
                    ->color(fn (ReservationStatus $state) => $state->couleur()),

                TextColumn::make('expire_le')
                    ->label('Échéance')
                    ->date('j M Y')
                    ->sortable()
                    /*
                     * Une echeance depassee sans paiement doit sauter aux yeux
                     * avant meme que le menage du matin ne passe.
                     */
                    ->color(fn (Reservation $r) => $r->estPerimee() ? 'danger' : null)
                    ->description(fn (Reservation $r) => $r->estPerimee() ? 'délai passé' : null)
                    ->placeholder('—'),

                TextColumn::make('paye_le')
                    ->label('Reçu le')
                    ->dateTime('j M Y')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                SelectFilter::make('statut')
                    ->label('Statut')
                    ->options(ReservationStatus::options()),
            ])

            ->recordActions([
                EditAction::make()->label('Ouvrir'),
            ])

            ->emptyStateHeading('Aucune réservation')
            ->emptyStateDescription(
                'Une réservation se crée après la visite : vous choisissez le chaton et la '
                .'famille, le site fabrique un lien de paiement, et le chaton est bloqué '
                .'dès que l’acompte arrive.'
            );
    }
}
