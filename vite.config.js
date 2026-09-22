import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

// Pas de Tailwind : la charte vient de la maquette validee (resources/css/app.css).
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/map.js'],
            refresh: true,
        }),
    ],
});
