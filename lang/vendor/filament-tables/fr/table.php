<?php

/* Meme principe : on ne comble que ce qui manque. */
return [

    'loading' => 'Chargement…',

    'columns' => [
        'icon' => [
            'boolean' => [
                'true'  => 'Oui',
                'false' => 'Non',
            ],
        ],
    ],

    'actions' => [
        // Utilisee par les listes qu'on peut reordonner a la souris : les
        // questions frequentes et les etapes d'une portee.
        'reorder_record' => [
            'label' => 'Déplacer la ligne :key',
        ],
        'toggle_record_content' => [
            'label' => 'Déplier ou replier la ligne :key',
        ],
    ],

    'column_manager' => [
        'actions' => [
            'reorder' => [
                'label' => 'Déplacer la colonne',
            ],
        ],
    ],

];
