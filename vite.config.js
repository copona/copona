import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
    build: {
        lib: {
            entry: resolve(__dirname, 'admin/src/js/editor.js'),
            name: 'CoponaEditor',
            formats: ['iife'],
            fileName: () => 'editor.bundle.js',
        },
        outDir: 'admin/view/javascript/dist',
        emptyOutDir: true,
        cssCodeSplit: false,
    },
});
