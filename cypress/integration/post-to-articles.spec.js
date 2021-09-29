/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

describe('Articles', () => {
  before(() => {

  });

  after(() => {

  });

  it('Posts are renamed to Articles', () => {
    cy.loginUser();
    cy.visit("wp-admin/edit.php");
    cy.get('h1').contains("Articles");
    cy.get("a.page-title-action").first().should('have.text', "Add Article");
    cy.get(".dashicons-admin-post").next().should('have.text', "Articles");
    cy.get("#menu-posts li a").eq(1).should('have.text', "Add Article");
    cy.get("#menu-posts li a").eq(3).should('have.text', "Tags");
  });

});
