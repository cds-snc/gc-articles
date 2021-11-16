Cypress.Commands.add('addUserCap', (userName, cap) => {
  cy.exec(`wp-env run tests-cli "wp user add-cap ${userName} ${cap}"`, { failOnNonZeroExit: false });
});
