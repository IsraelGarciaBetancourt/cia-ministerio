const defaultTheme = require('tailwindcss/defaultTheme');
const forms = require('@tailwindcss/forms');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                primary: {
                    50: "#fdf9d7",
                    100: "#F2EC94",
                    200: "#F2CB05",
                    300: "#D4B016",
                },
                dark: {
                    DEFAULT: "#0D0703",
                    100: "#1A120B",
                },
                light: {
                    DEFAULT: "#FFFFFF",
                    100: "#FAFAFA",
                    200: "#F2F2F2",
                },
                text: {
                    primary: "#0D0703",
                    muted: "#6F6F6F",
                },
                surface: {
                    DEFAULT: "#FFFFFF",
                    muted: "#F5F5F5",
                },
                border: {
                    DEFAULT: "#E6E6E6",
                },
            },

            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            }
        },
    },

    plugins: [forms],
};
