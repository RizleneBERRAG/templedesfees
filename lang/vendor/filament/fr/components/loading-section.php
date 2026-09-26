<?php

/*
 * Filament livre bien une traduction francaise, mais elle n'est pas complete :
 * soixante-treize clefs n'y figurent pas. Et comme la langue de repli de
 * l'application est le francais elle-meme, une clef manquante ne retombe sur
 * rien — elle s'affiche telle quelle a l'ecran, sous sa forme technique.
 *
 * L'eleveur voyait donc de vrais identifiants dans son back-office : un bouton
 * nomme « filament-forms::components.select.actions.clear.label » a cote de
 * chaque menu deroulant, par exemple.
 *
 * Deux reponses. Ce dossier traduit ce que cette administration affiche
 * vraiment, et APP_FALLBACK_LOCALE repasse a l'anglais pour que le reste soit
 * au moins lisible. Laravel fusionne ces fichiers avec ceux du paquet : on
 * n'ecrit donc que ce qui manque.
 */
return [
    'label' => 'Chargement…',
];
