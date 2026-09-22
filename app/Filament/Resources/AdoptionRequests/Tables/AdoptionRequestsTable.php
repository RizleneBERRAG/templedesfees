<?php

namespace App\Filament\Resources\AdoptionRequests\Tables;

use App\Filament\Resources\AdoptionRequests\Schemas\AdoptionRequestForm;
use App\Models\AdoptionRequest;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AdoptionRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // Les nouvelles demandes en tete, puis la plus recente d'abord.
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->orderByRaw("CASE WHEN statut = 'nouveau' THEN 0 ELSE 1 END")
                ->orderByDesc('created_at'))
            ->columns([
                TextColumn::make('created_at')
                    ->label('Reçue le')
                    ->date('d/m/Y')
                    ->description(fn (AdoptionRequest $d) => $d->created_at?->diffForHumans())
                    ->sortable(),

                TextColumn::make('prenom')
                    ->label('Famille')
                    ->formatStateUsing(fn (AdoptionRequest $d) => trim($d->prenom.' '.$d->nom))
                    ->description(fn (AdoptionRequest $d) => $d->code_postal)
                    ->searchable(['prenom', 'nom']),

                TextColumn::make('email')
                    ->label('Contact')
                    ->description(fn (AdoptionRequest $d) => $d->telephone)
                    ->searchable()
                    ->copyable(),

                TextColumn::make('kitten.nom')
                    ->label('Chaton')
                    ->placeholder(fn (AdoptionRequest $d) => $d->souhait ?: 'sans préférence'),

                TextColumn::make('statut')
                    ->label('Suivi')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => AdoptionRequestForm::STATUTS[$state] ?? $state)
                    ->color(fn (string $state) => match ($state) {
                        'nouveau'  => 'warning',
                        'accepte'  => 'success',
                        'refuse'   => 'danger',
                        'archive'  => 'gray',
                        default    => 'info',
                    })
                    ->sortable(),

                // La date de purge est une promesse faite sur la page Mentions
                // legales : elle doit etre visible, pas seulement respectee.
                TextColumn::make('a_purger_le')
                    ->label('Purge')
                    ->date('d/m/Y')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('statut')
                    ->label('Suivi')
                    ->options(AdoptionRequestForm::STATUTS),
            ])
            ->recordActions([EditAction::make()->label('Ouvrir')])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
