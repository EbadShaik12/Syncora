/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.jsx',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
                outfit: ['Outfit', 'system-ui', 'sans-serif'],
            },
            colors: {
                primary: {
                    50: '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe', 300: '#a5b4fc',
                    400: '#818cf8', 500: '#6366f1', 600: '#4f46e5', 700: '#4338ca',
                    800: '#3730a3', 900: '#312e81',
                },
                dark: {
                    900: '#030014', /* Deep premium dark */
                    800: '#0f0a2a',
                    700: '#1a103c',
                }
            },
        },
    },
    plugins: [],
}
