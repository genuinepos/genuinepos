import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/scripts/main.ts',
                'Modules/SAAS/Resources/assets/js/admin.js',
                'Modules/SAAS/Resources/assets/js/guest.js',
                'Modules/SAAS/Resources/assets/sass/guest.scss',
                'Modules/SAAS/Resources/assets/sass/admin.scss',
                'Modules/SAAS/Resources/assets/css/style.css',
            ],
            refresh: true,
        }),
    ],
});
