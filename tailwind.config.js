import defaultTheme from 'tailwindcss/defaultTheme';
<<<<<<< HEAD
=======
import forms from '@tailwindcss/forms';
>>>>>>> master

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
<<<<<<< HEAD
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
=======
        './resources/views/**/*.blade.php',
    ],

>>>>>>> master
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
<<<<<<< HEAD
    plugins: [],
=======

    plugins: [forms],
>>>>>>> master
};
