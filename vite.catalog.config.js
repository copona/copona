import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
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
