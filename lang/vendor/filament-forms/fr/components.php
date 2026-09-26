<?php

/*
 * Ce que Filament n'a pas traduit en francais et que ce back-office affiche.
 * Laravel fusionne ce fichier avec celui du paquet : seules les clefs ecrites
 * ici sont remplacees, les autres restent celles de Filament.
 */
return [

    'select' => [
        'actions' => [
            'clear' => [
                // Le bouton en croix a cote de chaque menu deroulant. Il
                // s'affichait sous son nom technique sur toutes les fiches.
                'label' => 'Effacer la sélection',
            ],
            'remove_option' => [
                'label' => 'Retirer :label',
            ],
        ],
        'search_label' => 'Rechercher',
    ],

    'file_upload' => [
        'editor' => [
            'label' => 'Retoucher l’image',
        ],
    ],

];
