import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './resources/**/*.jsx',
    ],

    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: '#ea1c4d',
                    rgb: 'rgb(234,28,77)',
                },
                success: {
                    DEFAULT: '#65c16e',
                    rgb: 'rgb(101,193,110)',
                },
                accent: {
                    DEFAULT: '#fbc761',
                    rgb: 'rgb(251,199,97)',
                },
                neutral: {
                    text: '#333333',
                    background: '#ffffff',
                },
            },
            fontFamily: {
                sans: ['Roboto', 'ui-sans-serif', 'system-ui'],
            },
        },
    },

    plugins: [forms],
};
