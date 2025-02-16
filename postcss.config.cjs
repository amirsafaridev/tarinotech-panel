const purgecss = require('@fullhuman/postcss-purgecss');
const cssnano = require('cssnano');
module.exports = {
    plugins: [
        require('postcss-import'),
        purgecss({
            content: [
                './Modules/**/*.php',
                './app/**/*.php',
                './resources/views/**/*.php',
                './public/res-admin/assets/**/*.js',

            ],
            css: [
                './public/res-admin/assets/css/style.css',
            ],
        }),
        cssnano({
            preset: [
                'default',
                {
                    discardComments: {
                        removeAll: true,
                    },
                },
            ],
        }),
    ],
    map: {
        inline: false
    },
};