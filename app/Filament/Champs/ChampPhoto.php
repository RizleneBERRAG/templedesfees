<?php

namespace App\Filament\Champs;

use Filament\Forms\Components\FileUpload;

/**
 * Le champ d'envoi de photo, partage par les fiches reproducteur, portee et
 * chaton.
 *
 * Toutes ces colonnes stockent un chemin relatif a public/ — « images/cats/x.webp »
 * — que les vues rendent avec asset(). Le disque « site » pointe sur public/ :
 * une photo envoyee d'ici atterrit donc au meme endroit et dans la meme
 * convention que les 36 photos posees a la main au depart.
 */
class ChampPhoto
{
    public static function make(string $nom, string $libelle, ?string $aide = null): FileUpload
    {
        return FileUpload::make($nom)
            ->label($libelle)
            ->disk('site')
            ->directory('images/cats')
            ->visibility('public')
            ->image()
            ->imageEditor()
            ->maxSize(6144)
            ->acceptedFileTypes(['image/webp', 'image/jpeg', 'image/png'])
            ->helperText($aide ?? 'WebP de préférence, 6 Mo maximum.');
    }
}
