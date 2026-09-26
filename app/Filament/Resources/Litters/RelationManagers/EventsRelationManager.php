<?php

namespace App\Filament\Resources\Litters\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Les etapes d'une portee, celles que la fiche d'un chaton deroule.
 *
 * Naissance, premier vaccin, identification, age legal de cession, depart :
 * c'est la chronologie que les familles lisent pour savoir ou en est leur
 * chaton. Elle n'existait que par le seed — l'eleveur ne pouvait ni cocher
 * une etape faite, ni en ajouter une.
 *
 * Une etape peut n'avoir aucune date : « au depart » n'est pas un jour, c'est
 * un moment. D'ou le libelle libre a cote de la date.
 */
class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    /*
     * Pas de chargement differe : ces quelques lignes sont la raison
     * meme d'ouvrir la fiche. Les attendre derriere un « Chargement… »
     * n'economise rien et donne l'impression que la page est cassee.
     */
    protected static bool $isLazy = false;

    protected static ?string $title = 'Les étapes de la portée';

    protected static ?string $modelLabel = 'étape';

    protected static ?string $pluralModelLabel = 'étapes';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('libelle')
                    ->label('Étape')
                    ->required()
                    ->maxLength(120)
                    ->columnSpanFull()
                    ->helperText('« Naissance », « Première vaccination », « Départ possible »…'),

                DatePicker::make('date_evenement')
                    ->label('Date')
                    ->native(false)
                    ->displayFormat('d/m/Y'),

                TextInput::make('date_libelle')
                    ->label('Ou, à défaut de date')
                    ->maxLength(60)
                    ->helperText('« Au départ », « Vers 12 semaines »… Utilisé quand aucune date précise n’existe.'),

                TextInput::make('ordre')
                    ->label('Ordre d’affichage')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Toggle::make('est_fait')
                    ->label('Étape franchie')
                    ->helperText('Cochée, elle s’affiche en vert sur la fiche du chaton.'),

                Toggle::make('est_jalon')
                    ->label('Jalon important')
                    ->helperText('L’âge légal de cession, par exemple : il ressort davantage.'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('libelle')
            ->defaultSort('ordre')
            ->reorderable('ordre')
            ->emptyStateHeading('Aucune étape')
            ->emptyStateDescription('Sans étapes, la fiche des chatons de cette portée n’affiche aucune chronologie.')
            ->columns([
                TextColumn::make('libelle')
                    ->label('Étape')
                    ->wrap(),

                TextColumn::make('date_evenement')
                    ->label('Quand')
                    ->date('d/m/Y')
                    ->placeholder(fn ($record): string => $record->date_libelle ?: '—')
                    ->sortable(),

                IconColumn::make('est_fait')
                    ->label('Franchie')
                    ->boolean(),

                IconColumn::make('est_jalon')
                    ->label('Jalon')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make()->label('Ajouter une étape'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
