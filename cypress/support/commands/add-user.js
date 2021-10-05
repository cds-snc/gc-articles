

Cypress.Commands.add('addUser', (userName, password, role, autoLogin = true) => {
  if (autoLogin) {
    cy.login();
  }
  
  cy.exec(`wp-env run tests-cli "wp user create ${userName} ${userName}@example.com --role=${role} --user_pass=${password}"`, { failOnNonZeroExit: false });
});

