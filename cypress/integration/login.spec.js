/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

describe('Login', () => {
  it('Can view styled login page', () => {
    const host = Cypress.config().baseUrl;

    cy.visit("/login");
    cy.screenshot();
    cy.get("#login h1 a").should("have.text", "Canadian Digital Service");
    cy.get('#login h1 a').should('have.css', 'background-image', 'url("'+host+'/wp-content/mu-plugins/cds-base/images/site-login-logo.svg")')
    cy.get('.login form label').should('have.css', 'font-size', '16px')
  });

  it('Login redirect to dashboard', () => {
    cy.login();
    const host = Cypress.config().baseUrl;
    const url = cy.url()
    cy.url().should('eq', `${host}/wp-admin/index.php`)
  });

  it('Can view styled dashboard', () => {
    cy.login();

    cy.get("body").should("have.css", "font-size", "14.5px"); // body font-size increased to 14.5
    cy.get("#wpadminbar").should("have.css", "height", "36px"); // admin bar height increased to 36
    cy.get("h1").should("have.css", "font-size", "30px"); // h1 font-size increased to 30
  });

});
