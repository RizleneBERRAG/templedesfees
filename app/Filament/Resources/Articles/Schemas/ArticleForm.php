<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Filament\Champs\ChampPhoto;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make("L'article")
                    ->description("Le titre et le chapeau sont ce que voient les moteurs de recherche et les réseaux sociaux : écrivez-les en pensant à quelqu'un qui ne connaît pas encore l'élevage.")
                    ->columns(2)
                    ->schema([
                        TextInput::make('titre')
                            ->label('Titre')
                            ->required()
                            ->maxLength(140)
                            ->live(onBlur: true)
                            // Le slug se remplit tout seul a la creation, jamais
                            // ensuite : une adresse deja partagee ne doit pas
                            // changer parce qu'on corrige une coquille au titre.
                            ->afterStateUpdated(function ($state, $set, $operation) {
                                if ($operation === 'create') {
                                    $set('slug', Str::slug((string) $state));
                                }
                            })
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->label('Adresse de la page')
                            ->helperText('Se remplit tout seul. Ne la changez plus une fois l’article publié : les liens déjà partagés cesseraient de fonctionner.')
                            ->required()
                            ->maxLength(160)
                            ->unique(ignoreRecord: true)
                            ->prefix('/articles/'),

                        Select::make('categorie')
                            ->label('Rubrique')
                            ->options([
                                'Conseils'          => 'Conseils',
                                'Santé'             => 'Santé',
                                'Vie de l’élevage'  => 'Vie de l’élevage',
                                'Le Maine Coon'     => 'Le Maine Coon',
                            ])
                            ->native(false)
                            ->placeholder('Sans rubrique'),

                        Textarea::make('chapeau')
                            ->label('Chapeau')
                            ->helperText('Deux ou trois phrases. C’est ce qui s’affiche dans la liste, dans Google et quand on partage le lien.')
                            ->required()
                            ->rows(3)
                            ->maxLength(320)
                            ->columnSpanFull(),
                    ]),

                Section::make('Le texte')
                    ->description('Écrivez normalement. Une ligne vide sépare deux paragraphes. Pour un sous-titre, commencez la ligne par ## — pour une liste, par un tiret. **Gras** et *italique* fonctionnent aussi.')
                    ->schema([
                        Textarea::make('corps')
                            ->label(false)
                            ->required()
                            ->rows(22)
                            ->columnSpanFull(),
                    ]),

                Section::make('Illustration et publication')
                    ->columns(2)
                    ->schema([
                        ChampPhoto::make('photo_principale', 'Photo de l’article',
                            'Sans photo, la liste affiche une image de l’élevage.')
                            ->columnSpanFull(),

                        DatePicker::make('date_publication')
                            ->label('Date de publication')
                            ->helperText('Une date à venir programme l’article : il reste invisible jusqu’à ce jour-là.')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->default(now()),

                        Toggle::make('est_publie')
                            ->label('Publié')
                            ->helperText('Décoché, l’article reste un brouillon visible de vous seule.')
                            ->inline(false),
                    ]),
            ]);
    }
}
