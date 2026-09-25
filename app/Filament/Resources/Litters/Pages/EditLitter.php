<?php

namespace App\Filament\Resources\Litters\Pages;

use App\Filament\Resources\Litters\LitterResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLitter extends EditRecord
{
    protected static string $resource = LitterResource::class;

    /** Meme regle que pour le chaton : une facture emise retient la portee. */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn (): bool => $this->getRecord()->peutEtreSupprimee()),

            Action::make('suppression-impossible')
                ->label('Suppression impossible')
                ->icon('heroicon-o-lock-closed')
                ->color('gray')
                ->disabled()
                ->tooltip('Un chaton de cette portée porte une réservation '
                    .'encaissée : sa facture serait effacée avec la portée.')
                ->visible(fn (): bool => ! $this->getRecord()->peutEtreSupprimee()),
        ];
    }
}
