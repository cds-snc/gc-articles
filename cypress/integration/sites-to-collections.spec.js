/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

describe('Collections', () => {
  before(() => {

  });

  after(() => {

  });

  it('Sites are renamed to Collections', () => {
    cy.login();
    cy.visitDashboard();
    cy.get('#wp-admin-bar-view-site a').first().should('have.text', "Visit Collection");
    cy.get(".welcome-panel-column .button-hero").first().should('have.text', "Customize Your Collection");
  });

});
