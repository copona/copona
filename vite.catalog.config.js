import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
    css: {
        preprocessorOptions: {
            scss: {
                // Bootstrap 5.3.3 uses legacy Sass APIs — silence until Bootstrap upgrades
                silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'if-function'],
            },
        },
    },
    build: {
        lib: {
            entry: resolve(__dirname, 'themes/default/assets/js/catalog-entry.js'),
            formats: ['es'],
            fileName: () => '_catalog-unused',
            cssFileName: 'main',
        },
        outDir: 'themes/default/assets/css',
        emptyOutDir: false,
        cssCodeSplit: false,
    },
});
