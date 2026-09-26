<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

/**
 * Une question de la page « Questions frequentes ».
 *
 * Elles n'existaient que par le seed : la page repondait a des questions que
 * l'eleveur ne pouvait ni corriger, ni completer le jour ou une famille lui
 * posait la meme pour la dixieme fois.
 *
 * Les reponses nourrissent aussi la donnee structuree de la page, celle que
 * les moteurs affichent en accordeon sous le lien du site.
 */
class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('question')
                    ->label('Question')
                    ->required()
                    ->maxLength(200)
                    ->columnSpanFull()
                    ->helperText('Écrite comme une famille la poserait : « Peut-on venir voir les chatons ? »'),

                Textarea::make('reponse')
                    ->label('Réponse')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull(),

                TextInput::make('ordre')
                    ->label('Ordre d’affichage')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Toggle::make('est_publiee')
                    ->label('Publiée')
                    ->default(true)
                    ->helperText('Décochée, la question disparaît de la page et de la donnée structurée.'),
            ]);
    }
}
