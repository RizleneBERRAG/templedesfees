<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make("L'avis")
                    ->description("Recopiez l'avis tel qu'il est publié sur la fiche Google de l'élevage, sans le reformuler : le texte appartient à la personne qui l'a écrit.")
                    ->columns(2)
                    ->schema([
                        // Pas de champ « nom » : la page Mentions legales s'engage a ne
                        // publier les temoignages que sous le prenom seul. La contrainte
                        // est dans le schema, pas seulement dans la consigne.
                        TextInput::make('email')
                            ->label('Email de l’auteur')
                            ->helperText('Renseigné par le visiteur s’il a déposé son avis sur le site. Jamais affiché.')
                            ->email()
                            ->maxLength(150)
                            ->columnSpanFull(),

                        TextInput::make('prenom')
                            ->label('Prénom')
                            ->helperText('Prénom seul. Aucun nom de famille n’est publié sur le site.')
                            ->required()
                            ->maxLength(80),

                        Select::make('note')
                            ->label('Note')
                            ->options([
                                5 => '★★★★★',
                                4 => '★★★★☆',
                                3 => '★★★☆☆',
                                2 => '★★☆☆☆',
                                1 => '★☆☆☆☆',
                            ])
                            ->default(5)
                            ->required(),

                        Textarea::make('texte')
                            ->label('Texte de l’avis')
                            ->rows(5)
                            ->maxLength(1500)
                            ->required()
                            ->columnSpanFull(),

                        // C'est cette date qui classe les avis sur le site : le plus
                        // recent en premier. Il n'y a plus de champ d'ordre a la main.
                        DatePicker::make('publie_le')
                            ->label('Date de l’avis')
                            ->displayFormat('d/m/Y')
                            ->default(now())
                            ->required()
                            ->helperText('Les avis s’affichent du plus récent au plus ancien.'),
                    ]),

                Section::make('Publication')
                    ->description("La page Mentions légales engage l'élevage à ne publier un témoignage qu'avec l'accord écrit de son auteur. Assurez-vous de l'avoir avant de cocher.")
                    ->columns(1)
                    ->schema([
                        Toggle::make('est_publie')
                            ->label('Afficher cet avis sur la page Contact')
                            ->default(true),
                    ]),
            ]);
    }
}
