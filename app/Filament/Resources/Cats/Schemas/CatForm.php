<?php

namespace App\Filament\Resources\Cats\Schemas;

use App\Enums\CatRole;
use App\Filament\Champs\ChampPhoto;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Identité')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nom')
                            ->label('Nom')
                            ->required()
                            ->maxLength(80)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),

                        TextInput::make('slug')
                            ->label('Adresse de la fiche')
                            ->helperText('Apparaît dans l’URL. Se remplit tout seul depuis le nom.')
                            ->required()
                            ->maxLength(120)
                            ->unique(ignoreRecord: true),

                        Select::make('sexe')
                            ->label('Sexe')
                            ->options(['male' => 'Mâle', 'femelle' => 'Femelle'])
                            ->required(),

                        Select::make('role')
                            ->label('Rôle dans l’élevage')
                            ->options(CatRole::class)
                            ->required(),

                        TextInput::make('robe')
                            ->label('Robe')
                            ->placeholder('Brown tabby spotted rosetted')
                            ->maxLength(120),

                        TextInput::make('annee_naissance')
                            ->label('Année de naissance')
                            ->numeric()
                            ->minValue(1990)
                            ->maxValue((int) date('Y')),

                        DatePicker::make('date_naissance')
                            ->label('Date de naissance')
                            ->displayFormat('d/m/Y')
                            ->helperText('Facultative si seule l’année est connue.'),

                        Textarea::make('description')
                            ->label('Description')
                            ->rows(4)
                            ->maxLength(2000)
                            ->columnSpanFull(),
                    ]),

                Section::make('Pedigree et identification')
                    ->description("Ces numéros ne conditionnent pas la publication d'une fiche reproducteur — contrairement aux fiches chaton, soumises à l'obligation légale des annonces de cession.")
                    ->columns(2)
                    ->schema([
                        TextInput::make('loof_numero')
                            ->label('Numéro LOOF')
                            ->maxLength(40),

                        TextInput::make('icad_numero')
                            ->label("Numéro d'identification ICAD")
                            ->placeholder('250 269 000 000 000')
                            ->maxLength(40),
                    ]),

                Section::make('Photos')
                    ->columns(2)
                    ->schema([
                        ChampPhoto::make('photo_principale', 'Photo principale',
                            'Celle qui illustre la fiche et les vignettes de la liste.'),

                        ChampPhoto::make('photo_secondaire', 'Photo secondaire',
                            'Affichée en second sur la fiche. Facultative.'),
                    ]),

                Section::make('Affichage')
                    ->columns(2)
                    ->schema([
                        TextInput::make('ordre')
                            ->label('Ordre d’affichage')
                            ->helperText('Du plus petit au plus grand, dans la page L’élevage.')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Toggle::make('est_publie')
                            ->label('Afficher cette fiche sur le site')
                            ->default(true),
                    ]),
            ]);
    }
}
