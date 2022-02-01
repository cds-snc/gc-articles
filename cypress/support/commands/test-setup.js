Cypress.Commands.add('testSetup', (index = 0) => {
  cy.exec('npm run wp-env:clean');
  cy.exec('npm run wp-env:test:setup', {
    timeout: 60000
  })
});