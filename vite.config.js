import { defineConfig } from 'vite'
import laravel, { refreshPaths } from 'laravel-vite-plugin'

export default defineConfig({
    base: '/build/',
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/filament/user/theme.css', 
            'resources/css/filament/admin/theme.css',
            'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
