<?php

namespace App\Filament\Resources\Reservations\Pages;

use App\Filament\Resources\Reservations\ReservationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReservation extends CreateRecord
{
    protected static string $resource = ReservationResource::class;

    /**
     * On reste sur la fiche apres creation plutot que de revenir a la liste :
     * la premiere chose a faire est de copier le lien de paiement, et il est
     * sur la fiche.
     */
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('edit', ['record' => $this->record]);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Réservation créée. Le lien de paiement est prêt à être envoyé.';
    }
}
