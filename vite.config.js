import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            // Selbst gehostet über Bunny: die Schriften werden beim Bauen
            // heruntergeladen und mit ausgeliefert. Kein Google-Fonts-Aufruf,
            // also eine Fremdverbindung weniger und ein Absatz weniger in der
            // Datenschutzerklärung.
            fonts: [
                // Fredoka: Überschriften. Rund und freundlich – das ist die
                // Handschrift der Marke und bleibt.
                bunny('Fredoka', { weights: [500, 600] }),

                // Inter: Fließtext. Vorher lief auch der über Roboto Mono;
                // auf Absatzlänge liest sich Monospace zäh und bricht unruhig
                // um. Inter ist für genau diese Größen gezeichnet.
                bunny('Inter', { weights: [400, 500, 600] }),

                // Roboto Mono: nur noch Akzent – Labels, Tags, Preise, Daten,
                // die Zeile über der Überschrift. Der technische Charakter
                // bleibt als Würze, statt den ganzen Text auszubremsen.
                bunny('Roboto Mono', { weights: [400, 500] }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
