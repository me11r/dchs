module.exports = {
    'root': true,
    'parserOptions': {
        'parser': 'babel-eslint',
        'ecmaVersion': 2017,
        'sourceType': 'module'
    },
    'env': {
        'browser': true
    },
    'plugins': [
        'import',
        'vue'
    ],
    'extends': [
        'standard',
        'plugin:vue/strongly-recommended'
    ],
    'rules': {
        'indent': [
            'error',
            4
        ],
        'vue/html-indent': [
            'error',
            4
        ],
        'semi': [
            'error',
            'always'
        ],
        'no-debugger': process.env.NODE_ENV === 'production' ? 2 : 0,
        'space-before-function-paren': 'off'
    }
};
