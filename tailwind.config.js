import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
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
                    active:  '#0B4A30',
                    light:   '#E6F4ED',
                },
                'taruma-teal':   { DEFAULT: '#0C4E5B' },
                'harvest-gold':  { DEFAULT: '#D97706', light: '#FEF3C7' },
                'sanggabuana': {
                    DEFAULT: '#0F172A',
                    body:    '#334155',
                    muted:   '#6a6a6a',
                    hairline:'#dddddd',
                    soft:    '#ebebeb',
                },
                'rice-husk':     { DEFAULT: '#FAF9F5' },
            },
            borderRadius: {
                'asymmetric':    '0 24px 0 24px',
                'asymmetric-sm': '0 12px 0 12px',
            },
            spacing: {
                'section': '80px',
                'section-sm': '48px',
            },
        },
    },

    plugins: [forms],
};

