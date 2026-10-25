import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import tailwindcss from '@tailwindcss/vite';

// /api so'rovlari Laravel serveriga yo'naltiriladi (CORS kerak bo'lmaydi)
export default defineConfig({
    plugins: [react(), tailwindcss()],
    // Ildizdagi (Laravel Blade uchun) postcss.config.js ni qidirmasin
    css: { postcss: {} },
    server: {
        port: 5174,
        strictPort: true,
        proxy: {
            '/api': 'http://localhost:8000',
        },
    },
});
