import eslint from '@eslint/js';
import prettier from 'eslint-config-prettier';

export default [
    {
        ignores: ['node_modules/**', 'public/build/**', 'storage/**', 'vendor/**'],
    },

    eslint.configs.recommended,

    prettier,
];
