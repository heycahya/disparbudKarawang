import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'karawang-emerald': {
                    DEFAULT: '#0F5E3D',
                    active: '#0B4A30',
                    disabled: '#A7F3D0',
                    light: '#E6F4ED',
                },
                'taruma-teal': {
                    DEFAULT: '#0C4E5B',
                    dark: '#08363F',
                    light: '#E0F2F5',
                },
                'harvest-gold': {
                    DEFAULT: '#D97706',
                    hover: '#B45309',
                    light: '#FEF3C7',
                },
                'sanggabuana-slate': {
                    DEFAULT: '#0F172A',
                    body: '#334155',
                    muted: '#64748B',
                    soft: '#94A3B8',
                    border: '#CBD5E1',
                    hairline: '#E2E8F0',
                },
                'rice-husk': {
                    DEFAULT: '#FAF9F5',
                },
            },
            borderRadius: {
                'asymmetric': '1rem',
                'asymmetric-sm': '0.5rem',
            },
        },
    },

    plugins: [forms],
};

