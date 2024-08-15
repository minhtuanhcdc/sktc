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
        './resources/js/**/*.vue',
       
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['serif', ...defaultTheme.fontFamily.serif],
                Times: ['Times New Roman'],
            },
            backgroundImage: {
              
                'home': "url('/storage/images/bg_1.png')",
              },
              screens: {
                '2xl': '1536px',
                '5xl': '64rem',
                '6xl': '72rem',
                // => @media (min-width: 1536px) { ... }
              },
              maxWidth: {
                '1/2': '50%',
              },
              colors:{
                hcdc1:'#00828F',
                hcdc2:'#F36F21'
              }

        },
    },

    plugins: [forms, typography,require('@tailwindcss/line-clamp')],
};
