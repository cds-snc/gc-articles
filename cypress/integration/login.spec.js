/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

describe('Login', () => {
  it('Can view styled login page', () => {
    const host = Cypress.config().baseUrl;

    cy.visit("/wp-login.php");
    cy.screenshot();
    cy.get("#login h1 a").should("have.text", "Canadian Digital Service");
    cy.get('#login h1 a').should('have.css', 'background-image', 'url("'+host+'/wp-content/plugins/cds-base/images/site-login-logo.svg")')
  });

  it('Login redirect to dashboard', () => {
    cy.loginUser();
    const host = Cypress.config().baseUrl;
    const url = cy.url()
    cy.url().should('eq', `${host}/wp-admin/index.php`)
  });


});
