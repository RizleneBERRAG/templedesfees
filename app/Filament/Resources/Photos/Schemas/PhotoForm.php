<?php

namespace App\Filament\Resources\Photos\Schemas;

use App\Models\Cat;
use App\Models\Kitten;
use App\Models\Litter;
use App\Filament\Champs\ChampPhoto;
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
                        /*
                         * Le meme champ que les fiches, et pour la meme
                         * raison. Il etait pose ici en FileUpload nu : la
                         * photo arrivait telle quelle, dans son format
                         * d'origine, sans reduction — et surtout sans que ses
                         * metadonnees soient effacees. Une photo de telephone
                         * porte les coordonnees GPS de l'endroit ou elle a ete
                         * prise : l'elevage ne publie pas son adresse, et la
                         * publiait pourtant dans chaque image ajoutee par
                         * cette rubrique.
                         */
                        ChampPhoto::make('chemin', 'Photo',
                            'Photo prise au téléphone acceptée : elle est redressée, allégée et ses données de localisation sont effacées avant publication.')
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

                        /*
                         * Volontairement facultatif. Une photo de galerie n'est
                         * rattachee a rien — c'est ce qui la definit : elle
                         * illustre l'elevage, pas une fiche. Le champ etait
                         * obligatoire, et le seul effet etait qu'aucune photo de
                         * galerie ne pouvait plus etre enregistree : ouvrir
                         * l'une des onze, changer sa legende, enregistrer, et le
                         * formulaire refusait tant qu'on ne lui avait pas
                         * attribue un chat ou une portee au hasard.
                         */
                        MorphToSelect::make('attachable')
                            ->label('Rattachée à')
                            ->types([
                                MorphToSelect\Type::make(Litter::class)->titleAttribute('code')->label('Portée'),
                                MorphToSelect\Type::make(Cat::class)->titleAttribute('nom')->label('Reproducteur'),
                                MorphToSelect\Type::make(Kitten::class)->titleAttribute('nom')->label('Chaton'),
                            ])
                            ->searchable()
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
