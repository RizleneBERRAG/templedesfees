<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use App\Models\ContactMessage;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // Le message est en lecture seule : on ne reecrit pas les mots
                // de quelqu'un. Seul le suivi appartient a l'elevage.
                Section::make('Le message')
                    ->columns(2)
                    ->schema([
                        Placeholder::make('objet')->label('Objet')
                            ->content(fn (?ContactMessage $record) => $record?->objetLibelle()),
                        Placeholder::make('recu_le')->label('Reçu le')
                            ->content(fn (?ContactMessage $record) => $record?->created_at?->translatedFormat('j F Y à H:i')),
                        Placeholder::make('identite')->label('De la part de')
                            ->content(fn (?ContactMessage $record) => trim($record?->prenom.' '.$record?->nom)),
                        Placeholder::make('contact')->label('Pour répondre')
                            ->content(fn (?ContactMessage $record) => trim($record?->email.'   '.$record?->telephone)),
                        Placeholder::make('message')->label('Message')
                            ->content(fn (?ContactMessage $record) => $record?->message)
                            ->columnSpanFull(),
                    ]),

                Section::make('Suivi')
                    ->columns(2)
                    ->schema([
                        Toggle::make('est_traite')
                            ->label('Message traité'),
                        Placeholder::make('a_purger_le')->label('Suppression automatique le')
                            ->content(fn (?ContactMessage $record) => $record?->a_purger_le?->translatedFormat('j F Y') ?: '—'),
                    ]),
            ]);
    }
}
