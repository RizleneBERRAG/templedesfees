<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use App\Models\ContactMessage;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // Les messages non traites en tete, puis le plus recent d'abord.
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->orderBy('est_traite')
                ->orderByDesc('created_at'))
            ->columns([
                TextColumn::make('created_at')
                    ->label('Reçu le')
                    ->date('d/m/Y')
                    ->description(fn (ContactMessage $m) => $m->created_at?->diffForHumans())
                    ->sortable(),

                TextColumn::make('objet')
                    ->label('Objet')
                    ->badge()
                    ->formatStateUsing(fn (ContactMessage $m) => $m->objetLibelle()),

                TextColumn::make('prenom')
                    ->label('De')
                    ->formatStateUsing(fn (ContactMessage $m) => trim($m->prenom.' '.$m->nom))
                    ->searchable(['prenom', 'nom']),

                TextColumn::make('email')
                    ->label('Pour répondre')
                    ->description(fn (ContactMessage $m) => $m->telephone)
                    ->searchable()
                    ->copyable(),

                TextColumn::make('message')
                    ->label('Message')
                    ->limit(60)
                    ->wrap()
                    ->searchable(),

                TextColumn::make('est_traite')
                    ->label('Suivi')
                    ->badge()
                    ->state(fn (ContactMessage $m) => $m->est_traite ? 'Traité' : 'À traiter')
                    ->color(fn (ContactMessage $m) => $m->est_traite ? 'success' : 'warning'),
            ])
            ->filters([
                SelectFilter::make('objet')
                    ->label('Objet')
                    ->options(ContactMessage::OBJETS),

                TernaryFilter::make('est_traite')
                    ->label('Suivi')
                    ->placeholder('Tous')
                    ->trueLabel('Traités')
                    ->falseLabel('À traiter'),
            ])
            ->recordActions([EditAction::make()->label('Ouvrir')])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
