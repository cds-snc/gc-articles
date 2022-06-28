module.exports = {
    webpack: function (config, env) {
        config.externals = Object.assign(config.externals || {}, {
            wp: 'wp',
            react: 'React',
            'react-dom': 'ReactDOM'
        });
        return config;
    },
};