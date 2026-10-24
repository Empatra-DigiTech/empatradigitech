import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/home.css',
                'resources/js/home.js',
                // Admin: CSS per fitur (dipindah dari <style> inline di Blade)
                'resources/css/patra/invoice-create.css',
                'resources/css/patra/invoice-edit.css',
                'resources/css/patra/invoice-show.css',
            ],
            refresh: true,
        }),
    ],
});
