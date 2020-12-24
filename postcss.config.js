module.exports = {
    from: 'resources/css/app.css',
    to: 'public/css/app.css',
    plugins: [
        require('postcss-import'),
        require('tailwindcss'),
    ],
}
