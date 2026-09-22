<?php

namespace App\Filament\Resources\AdoptionRequests\Pages;

use App\Filament\Resources\AdoptionRequests\AdoptionRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAdoptionRequests extends ListRecords
{
    protected static string $resource = AdoptionRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
