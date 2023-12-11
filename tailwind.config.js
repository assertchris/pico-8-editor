import forms from '@tailwindcss/forms';

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            gridTemplateColumns: {
                '8': 'repeat(8, minmax(0, 1fr))',
                '16': 'repeat(16, minmax(0, 1fr))',
                '32': 'repeat(32, minmax(0, 1fr))',
            },
        },
    },
    safelist: [
        'grid-cols-8',
        'grid-cols-16',
        'grid-cols-32',
    ],
    plugins: [forms],
};
