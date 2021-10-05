Cypress.Commands.add('login', (username = 'admin', password = 'password') => {
  cy.visit('/login');
  // somehow we need to wait for some time before entering the credentials
  // eslint-disable-next-line cypress/no-unnecessary-waiting
  cy.wait(500);
  cy.get('#user_login').clear().type(username);
  cy.get('#user_pass').clear().type(password);
  cy.get('#wp-submit').click({ force: true });
});

