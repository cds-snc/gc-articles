Cypress.Commands.add('login', (username = 'admin', password = 'password') => {
  cy.clearCookies();
  cy.visit('/wp-login.php');
  // somehow we need to wait for some time before entering the credentials
  // eslint-disable-next-line cypress/no-unnecessary-waiting
  cy.wait(500);
  cy.get('#user_login').clear().type(username);
  cy.get('#user_pass').clear().type(password);
  cy.get('#wp-submit').click({ force: true });

  if(username === 'admin') {
    cy.visit('/wp-admin/options-permalink.php');
    cy.visit('/wp-admin/index.php');
  }
});

