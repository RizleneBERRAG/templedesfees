<?php

namespace App\Filament\Resources\Kittens\Pages;

use App\Filament\Resources\Kittens\KittenResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKitten extends EditRecord
{
    protected static string $resource = KittenResource::class;

    /**
     * Le bouton Supprimer se retire des qu'un acompte a ete encaisse.
     *
     * La cle etrangere est en cascade : effacer la fiche emporterait la
     * reservation, son numero de facture et la somme recue. Le modele le
     * refuse de toute facon — voir KittenObserver — mais l'eleveuse ne doit
     * pas decouvrir la regle en tombant sur une erreur. On lui dit pourquoi
     * a la place.
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
                ->tooltip('Ce chaton porte une réservation encaissée : sa facture '
                    .'serait effacée avec la fiche.')
                ->visible(fn (): bool => ! $this->getRecord()->peutEtreSupprime()),
        ];
    }
}
