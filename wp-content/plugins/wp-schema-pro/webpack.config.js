const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const path = require('path');

module.exports = {
    ...defaultConfig,
    plugins: [
        ...defaultConfig.plugins,
    ],
    entry: {
        blocks: path.resolve(__dirname, 'src/blocks.js'),
    },
    resolve: {
        alias: {
            ...defaultConfig.resolve.alias,
            '@Controls': path.resolve(__dirname, 'wpsp-config/controls'),
            '@Components': path.resolve(__dirname, 'src/components/'),
        },
    },
    output: {
        filename: "[name].js",
        path: __dirname + "/dist",
    },
};
