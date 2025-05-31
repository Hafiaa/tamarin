/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                // Food card colors
                'food-green': '#4CAF50',
                'food-orange': '#FFC107',
                // Button colors
                'food-button': '#8D6E63',
                // Price bubble
                'price-bubble': '#CDDC39',
                // Text colors
                'food-text': '#FFFFFF',
                'food-dark': '#333333',
                // Background
                'food-bg': '#F5F5F5',
            },
            fontFamily: {
                sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
        },
    },
    plugins: [],
}
