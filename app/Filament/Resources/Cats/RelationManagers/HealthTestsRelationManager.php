<?php

namespace App\Filament\Resources\Cats\RelationManagers;

use App\Enums\HealthTestType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Les resultats de depistage d'un reproducteur.
 *
 * C'est ce que le site met le plus en avant — « resultats publies, y compris
 * ceux qui manquent » — et c'etait la seule donnee de la fiche que l'eleveur
 * ne pouvait pas toucher. Elle n'existait que par le seed.
 *
 * Le tableau de bord bouclait meme dans le vide : il annoncait « echographie
 * a refaire, plus d'un an », renvoyait vers la liste des reproducteurs, et
 * rien la-bas ne permettait d'en saisir une nouvelle.
 *
 * Les resultats se gerent donc ici, sur la fiche du chat, parce que c'est la
 * qu'on les lit et la qu'on les cherche au retour du veterinaire.
 */
class HealthTestsRelationManager extends RelationManager
{
    protected static string $relationship = 'healthTests';

    /*
     * Pas de chargement differe : ces quelques lignes sont la raison
     * meme d'ouvrir la fiche. Les attendre derriere un « Chargement… »
     * n'economise rien et donne l'impression que la page est cassee.
     */
    protected static bool $isLazy = false;

    protected static ?string $title = 'Résultats de dépistage';

    protected static ?string $modelLabel = 'résultat';

    protected static ?string $pluralModelLabel = 'résultats';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('type')
                    ->label('Examen')
                    ->options(HealthTestType::class)
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('resultat')
                    ->label('Résultat')
                    ->maxLength(120)
                    ->helperText('« N/N — indemne », « Normale », « À programmer »… Laissé vide, la fiche l’affiche comme attendu.'),

                /*
                 * La date porte tout le sens d'une echocardiographie : elle ne
                 * vaut que pour le jour ou elle a ete faite. Un test ADN, lui,
                 * vaut pour la vie — sa date est indicative.
                 */
                DatePicker::make('date_examen')
                    ->label('Date de l’examen')
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->maxDate(now()),

                TextInput::make('laboratoire')
                    ->label('Laboratoire')
                    ->maxLength(120),

                TextInput::make('veterinaire')
                    ->label('Vétérinaire')
                    ->maxLength(120),

                Textarea::make('commentaire')
                    ->label('Commentaire')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('resultat')
            ->defaultSort('type')
            ->emptyStateHeading('Aucun résultat saisi')
            ->emptyStateDescription('La fiche affiche « à compléter » tant qu’un examen n’a pas de résultat. C’est voulu : on ne masque pas ce qui n’est pas encore fait.')
            ->columns([
                TextColumn::make('type')
                    ->label('Examen')
                    ->formatStateUsing(fn (HealthTestType $state): string => $state->libelle())
                    ->wrap(),

                TextColumn::make('resultat')
                    ->label('Résultat')
                    ->placeholder('à compléter')
                    ->badge()
                    ->color(fn (?string $state): string => blank($state) ? 'warning' : 'success'),

                TextColumn::make('date_examen')
                    ->label('Fait le')
                    ->date('d/m/Y')
                    ->placeholder('—')
                    ->sortable(),

                TextColumn::make('laboratoire')
                    ->label('Laboratoire')
                    ->placeholder('—')
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make()->label('Ajouter un résultat'),
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
