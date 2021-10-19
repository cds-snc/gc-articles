/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

describe('Find users', () => {
  before(() => {

  });

  after(() => {

  });

  it('Can load the new page', () => {
    cy.login();
    cy.visit("wp-admin/users.php?page=users-find");
    cy.get('h1').contains("Find Users");
    cy.get("button.button-primary").first().should('have.text', "Find user");
  });

});
