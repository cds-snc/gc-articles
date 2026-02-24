const { defineConfig } = require('cypress');

module.exports = defineConfig({
  projectId: 'rv8iqi',
  chromeWebSecurity: false,
  video: false,
  screenshotOnRunFailure: false,
  retries: {
    runMode: 2,
    openMode: 0,
  },
  e2e: {
    baseUrl: 'http://localhost:8889',
    supportFile: 'cypress/support/e2e.js',
    setupNodeEvents(on, config) {
      // implement node event listeners here
    },
  },
});
