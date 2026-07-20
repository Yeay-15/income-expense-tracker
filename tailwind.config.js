import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    500: '#3b82f6',
                    600: '#0D6EFD',
                    700: '#0b5ed7',
                },
                success: {
                    50: '#ecfdf5',
                    100: '#d1fae5',
                    500: '#22c55e',
                    600: '#198754',
                    700: '#146c43',
                },
                danger: {
                    50: '#fef2f2',
                    100: '#fee2e2',
                    500: '#ef4444',
                    600: '#DC3545',
                    700: '#b02a37',
                },
                warning: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    400: '#FFC107',
                    500: '#e6ac00',
                },
                info: {
                    50: '#ecfeff',
                    100: '#cffafe',
                    400: '#0DCAF0',
                    500: '#0bb3d6',
                },
            },
        },
    },

    plugins: [forms],
};
