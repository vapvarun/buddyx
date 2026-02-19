const fs = require('fs');
const path = require('path');

const DEFAULT_CONFIG_PATH = path.join(__dirname, 'config/config.default.json');
const CUSTOM_CONFIG_PATH = path.join(__dirname, 'config/config.json');
const LOCAL_CONFIG_PATH = path.join(__dirname, 'config/config.local.json');

const loadConfig = (config, filePath) => {
	if (fs.existsSync(filePath)) {
		try {
			const fileContent = fs.readFileSync(filePath, 'utf-8');
			const fileConfig = JSON.parse(fileContent);
			return {
				...config,
				...fileConfig,
				dev: {
					...config.dev,
					...fileConfig.dev,
					browserSync: {
						...config.dev?.browserSync,
						...fileConfig.dev?.browserSync
					}
				}
			};
		} catch (e) {
			console.warn(`Warning: Could not parse ${filePath}`);
		}
	}
	return config;
};

let wpConfig = {};
if (fs.existsSync(DEFAULT_CONFIG_PATH)) {
	wpConfig = JSON.parse(fs.readFileSync(DEFAULT_CONFIG_PATH, 'utf-8'));
}

wpConfig = loadConfig(wpConfig, CUSTOM_CONFIG_PATH);
wpConfig = loadConfig(wpConfig, LOCAL_CONFIG_PATH);

const proxyURL = wpConfig.dev?.browserSync?.proxyURL || 'buddyx.local';
const protocol = wpConfig.dev?.browserSync?.https ? 'https' : 'http';
const baseURL = `${protocol}://${proxyURL}`;

module.exports = {
	ci: {
		collect: {
			url: [
				baseURL,
				`${baseURL}/sample-page/`,
				`${baseURL}/?p=1`,
			],
			numberOfRuns: 3,
			settings: {
				preset: 'desktop',
			},
		},
		assert: {
			assertions: {
				'categories:performance': ['warn', { minScore: 0.7 }],
				'categories:accessibility': ['error', { minScore: 0.8 }],
				'categories:best-practices': ['error', { minScore: 0.8 }],
				'categories:seo': ['error', { minScore: 0.8 }],
			},
		},
		upload: {
			target: 'temporary-public-storage',
		},
	},
};
