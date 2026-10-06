<?php

namespace App\Filament\Resources\Cats\Pages;

use App\Filament\Resources\Cats\CatResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCat extends EditRecord
{
    protected static string $resource = CatResource::class;

    /**
     * Le bouton disparaît quand la fiche est rattachée à une portée, et un
     * bouton inerte prend sa place pour dire pourquoi.
     *
     * Le retirer sans rien mettre laisserait croire à un oubli ; laisser
     * l'éleveur cliquer pour tomber sur une erreur lui ferait découvrir la
     * règle de la pire façon. On la lui dit avant.
     */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn (): bool => $this->getRecord()->peutEtreSupprime()),

            Action::make('suppression-impossible')
                ->label('Suppression impossible')
                ->icon('heroicon-o-lock-closed')
                ->color('gray')
                ->disabled()
                ->tooltip(fn (): string => 'Ce chat est père ou mère de : '
                    .$this->getRecord()->portees()->pluck('code')->implode(', ')
                    .'. Effacer sa fiche viderait la ligne « Parents » sur celle '
                    .'de chacun de ses chatons.')
                ->visible(fn (): bool => ! $this->getRecord()->peutEtreSupprime()),
        ];
    }
}
