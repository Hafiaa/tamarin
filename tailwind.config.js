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
                // Primary color
                'primary': '#b9c24b',
                // Food card colors
                'food-green': '#b9c24b',  // Updated to primary green
                'food-orange': '#FFC107',
                // Button colors
                'food-button': '#b9c24b',  // Updated to primary green
                // Price bubble
                'price-bubble': '#b9c24b', // Updated to primary green
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
