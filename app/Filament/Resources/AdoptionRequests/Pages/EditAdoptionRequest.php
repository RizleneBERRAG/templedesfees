<?php

namespace App\Filament\Resources\AdoptionRequests\Pages;

use App\Filament\Resources\AdoptionRequests\Actions\PreparerLaReservation;
use App\Filament\Resources\AdoptionRequests\AdoptionRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAdoptionRequest extends EditRecord
{
    protected static string $resource = AdoptionRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            PreparerLaReservation::make(),
            DeleteAction::make(),
        ];
    }
}
