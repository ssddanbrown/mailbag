const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: ['./storage/framework/views/*.php', './resources/views/**/*.blade.php'],

    theme: {
        screens: {
            'sm': '640px',
            'md': '768px',
            'lg': '1024px',
        },
        container: {
            center: true,
            padding: '2rem',
        },
        extend: {
            fontFamily: {
                sans: ['Roboto', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
