import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                themePrimary: '#0E2E50',    // Dark blue like Cashfree
                themeSecondary: '#0077FF',  // Bright blue accent
                themeAccent: '#00D1C7',     // Teal accent
                themeLight: '#F8FAFC',      // Light background
                themeDark: '#0A1F35',       // Darker blue for text
                appPrimary: '#2b3990',
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
            },
        },
    },

    plugins: [forms, typography],
};
