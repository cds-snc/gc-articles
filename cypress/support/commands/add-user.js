

Cypress.Commands.add('addUser', (userName, password, roleText) => {
  cy.loginUser();
  cy.visit("/wp-admin/user-new.php");
  cy.get('#user_login').type(userName);
  cy.get('#email').type(`${userName}@example.com`);
  cy.get('#pass1').clear();
  cy.get('#pass1').type(password);
  cy.get('#send_user_notification').uncheck();
  cy.get('.pw-checkbox').check();
  cy.get('#role').select(roleText);
  cy.get('form#createuser').submit();
  cy.get('#wp-admin-bar-top-secondary').trigger('mouseover');
  cy.get('#wp-admin-bar-logout a').click({force: true});
});

