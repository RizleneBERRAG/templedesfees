<?php

namespace App\Filament\Champs;

use App\Support\Photographe;
use Filament\Forms\Components\FileUpload;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

/**
 * Le champ d'envoi de photo, partage par les fiches reproducteur, portee,
 * chaton, article et galerie.
 *
 * Toutes ces colonnes stockent un chemin relatif a public/ —
 * « images/cats/x.webp » — que les vues rendent avec asset(). Le disque
 * « site » pointe sur public/ : une photo envoyee d'ici atterrit donc au meme
 * endroit et dans la meme convention que les photos posees a la main au
 * depart.
 *
 * L'enregistrement passe par le Photographe, qui redresse la photo, efface
 * ses metadonnees — les coordonnees GPS d'un telephone, notamment, alors que
 * l'elevage ne publie pas son adresse — la plafonne a 1200 px et fabrique les
 * deux reductions que <x-img> declare en srcset.
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
            ->maxSize(12288)
            ->acceptedFileTypes(['image/webp', 'image/jpeg', 'image/png'])
            ->helperText($aide ?? 'Photo prise au téléphone acceptée : elle est redressée, allégée et ses données de localisation sont effacées avant publication. 12 Mo maximum.')
            /*
             * Le parametre DOIT s'appeler $file.
             *
             * Filament evalue ce rappel en lui passant ['file' => $fichier],
             * et apparie d'abord par le nom. Un parametre nomme autrement
             * n'est pas reconnu : Filament tente alors de fabriquer un
             * UploadedFile par le conteneur, qui ne sait pas le construire,
             * et l'envoi echoue sur une BindingResolutionException.
             *
             * Il s'appelait $fichier. Aucune photo ne pouvait donc etre
             * deposee depuis une fiche chat, chaton, portee ou article — et
             * rien ne le disait, puisque tous les ecrans repondaient.
             */
            ->saveUploadedFileUsing(
                fn (TemporaryUploadedFile $file): string => Photographe::ranger($file)
            );
    }
}
