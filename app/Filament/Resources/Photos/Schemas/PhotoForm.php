<?php

namespace App\Filament\Resources\Photos\Schemas;

use App\Models\Cat;
use App\Models\Kitten;
use App\Models\Litter;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PhotoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Le fichier')
                    ->columns(1)
                    ->schema([
                        /*
                         * Depose directement dans public/images/cats via le disque
                         * "site", donc au meme endroit et dans la meme convention que
                         * les 36 photos posees a la main : la colonne chemin recoit
                         * "images/cats/<fichier>", que les vues rendent avec asset().
                         * Plus besoin de passer par php artisan photos:sync.
                         */
                        FileUpload::make('chemin')
                            ->label('Photo')
                            ->disk('site')
                            ->directory('images/cats')
                            ->visibility('public')
                            ->image()
                            ->imageEditor()
                            ->maxSize(6144)
                            ->acceptedFileTypes(['image/webp', 'image/jpeg', 'image/png'])
                            ->helperText('WebP de préférence, 6 Mo maximum. Le fichier est déposé dans public/images/cats.')
                            ->required(),

                        // Obligatoire a dessein : l'absence de texte alternatif etait
                        // l'un des reproches faits a l'ancien site (7 images sur 9 en
                        // accueil). Le back-office ne doit pas permettre de recommencer.
                        TextInput::make('alt')
                            ->label('Texte alternatif')
                            ->helperText('Ce que montre la photo, pour les lecteurs d’écran et si l’image ne charge pas. Obligatoire.')
                            ->required()
                            ->maxLength(160),

                        TextInput::make('legende')
                            ->label('Légende')
                            ->helperText('Affichée sous la photo en galerie. Facultative.')
                            ->maxLength(160),
                    ]),

                Section::make('Classement')
                    ->columns(2)
                    ->schema([
                        Select::make('categorie')
                            ->label('Catégorie')
                            ->options([
                                'chatons' => 'Chatons',
                                'adultes' => 'Adultes',
                                'maison'  => 'La maison',
                            ])
                            ->helperText('Sert au filtre de la galerie.'),

                        TextInput::make('ordre')
                            ->label('Ordre d’affichage')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        MorphToSelect::make('attachable')
                            ->label('Rattachée à')
                            ->types([
                                MorphToSelect\Type::make(Litter::class)->titleAttribute('code')->label('Portée'),
                                MorphToSelect\Type::make(Cat::class)->titleAttribute('nom')->label('Reproducteur'),
                                MorphToSelect\Type::make(Kitten::class)->titleAttribute('nom')->label('Chaton'),
                            ])
                            ->searchable()
                            ->required()
                            ->columnSpanFull(),

                        Toggle::make('est_couverture')
                            ->label('Photo de couverture'),

                        Toggle::make('est_publiee')
                            ->label('Visible sur le site')
                            ->default(true),
                    ]),
            ]);
    }
}
