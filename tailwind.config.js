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
                    DEFAULT: '#004b23',
                    hover: '#003d1d',
                    active: '#002e15',
                    disabled: '#A8FFD1',
                    light: '#E6F4ED',
                },
                'spruce-mint': {
                    DEFAULT: '#A8FFD1',
                    glow: '#52FFA3',
                    light: '#ECFDF5',
                },
                'harvest-gold': {
                    DEFAULT: '#F59E0B',
                    hover: '#D97706',
                    light: '#FEF3C7',
                },
                'taruma-teal': {
                    DEFAULT: '#0284C7',
                    dark: '#0369A1',
                    light: '#E0F2FE',
                },
                'sanggabuana-slate': {
                    DEFAULT: '#0B120E',
                    card: '#142018',
                    body: '#334155',
                    muted: '#64748B',
                    border: '#1F2E23',
                    hairline: '#E1E6E2',
                },
                'rice-husk': {
                    DEFAULT: '#FAFAF8',
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

