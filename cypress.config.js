import { defineConfig } from 'cypress'

export default defineConfig({
  video: true,
  screenshotOnRunFailure: true,
  retries: {
    runMode: 2,
    openMode: 0,
  },
  projectId: 'rv8iqi',
  chromeWebSecurity: false,
  e2e: {
    setupNodeEvents(on, config) {},
    baseUrl: 'http://localhost:8889',
    specPattern: 'cypress/e2e/**/*.{js,jsx,ts,tsx}',
  },
})
