    import { defineConfig } from 'vite';
    import laravel from 'laravel-vite-plugin';

    export default defineConfig({
        plugins: [
            laravel({
                // Añade la ruta a tu archivo HTML estático aquí
                input: [
                    'resources/css/app.css',
                    'resources/js/app.js',
                    'resources/views/frontend_template.html', // <-- Añade esta línea
                ],
                refresh: true,
            }),
        ],
        // Opcional: Si necesitas configurar el directorio base para los assets en Vercel
        // base: '/build/', // Descomentar si los assets no cargan y Vercel los sirve desde /build/assets/
    });
    