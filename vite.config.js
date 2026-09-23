import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

// Pas de Tailwind : la charte vient de la maquette validee (resources/css/app.css).
export default defineConfig({
    plugins: [
        laravel({
            // document.css n'est pas chargee par le site : elle ne sert qu'au
            // contrat et a la facture, qui s'impriment sur du papier blanc.
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/document.css',
            ],
            refresh: true,
        }),
    ],
});
