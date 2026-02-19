module.exports = {
	transform: {},
	testEnvironment: 'jsdom',
	moduleFileExtensions: ['js', 'jsx', 'ts', 'tsx'],
	testMatch: [
		'**/tests/js/**/*.test.{js,ts}',
		'**/assets/js/src/**/*.test.{js,ts}',
		'**/assets/blocks/**/src/*.test.{js,ts}'
	],
};
