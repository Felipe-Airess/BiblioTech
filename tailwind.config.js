import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                serif: ['Merriweather', ...defaultTheme.fontFamily.serif],
            },
            keyframes: {
                'brand-swap-a': {
                    '0%, 100%': { color: '#1E3A8A' },
                    '50%': { color: '#F59E0B' },
                },
                'brand-swap-b': {
                    '0%, 100%': { color: '#F59E0B' },
                    '50%': { color: '#1E3A8A' },
                },
            },
            animation: {
                'brand-swap-a': 'brand-swap-a 6s ease-in-out infinite',
                'brand-swap-b': 'brand-swap-b 6s ease-in-out infinite',
            },
        },
    },

    plugins: [forms],
};
