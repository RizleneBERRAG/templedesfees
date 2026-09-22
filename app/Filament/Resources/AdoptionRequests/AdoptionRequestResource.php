<?php

namespace App\Filament\Resources\AdoptionRequests;

use App\Filament\Resources\AdoptionRequests\Pages\EditAdoptionRequest;
use App\Filament\Resources\AdoptionRequests\Pages\ListAdoptionRequests;
use App\Filament\Resources\AdoptionRequests\Schemas\AdoptionRequestForm;
use App\Filament\Resources\AdoptionRequests\Tables\AdoptionRequestsTable;
use App\Models\AdoptionRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AdoptionRequestResource extends Resource
{
    protected static ?string $model = AdoptionRequest::class;

    protected static ?string $modelLabel = 'demande';

    protected static ?string $pluralModelLabel = 'demandes';

    protected static ?string $navigationLabel = 'Demandes d’adoption';

    protected static ?int $navigationSort = 6;

    protected static ?string $recordTitleAttribute = 'prenom';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxArrowDown;

    /*
     * Un dossier naît du formulaire du site, jamais d'ici : un écran de création
     * n'inviterait qu'à fabriquer de fausses demandes. La page de création est
     * donc retirée de getPages() en plus d'être interdite.
     */
    public static function canCreate(): bool
    {
        return false;
    }

    /** Les demandes que l'élevage n'a pas encore ouvertes. */
    public static function getNavigationBadge(): ?string
    {
        $n = AdoptionRequest::where('statut', 'nouveau')->count();

        return $n > 0 ? (string) $n : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Demandes non encore ouvertes';
    }

    public static function form(Schema $schema): Schema
    {
        return AdoptionRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdoptionRequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAdoptionRequests::route('/'),
            'edit' => EditAdoptionRequest::route('/{record}/edit'),
        ];
    }
}
