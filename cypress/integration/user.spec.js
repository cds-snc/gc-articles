/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

describe('User', () => {
  before(() => {

  });

  after(() => {
    cy.exec('npm run wp-env:clean');
  });

  it('Can login as IRCC user type', () => {
    cy.addUser('ircc', 'secret', 'IRCC');
    cy.loginUser('ircc', 'secret');

    cy.visitNotify();
    cy.get('h1').should('have.text', 'Send Notify Template');

    // user should not be able to access these pages
    const pages = [
      'edit.php',
      'edit.php?post_type=page',
      'edit-comments.php',
      'upload.php',
      'themes.php',
      'tools.php',
      'general.php'];

    pages.forEach((page) => {
      cy.visit(`wp-admin/${page}`);
      cy.get(".wp-die-message").should("have.text", "Sorry, you are not allowed to access this page.")
    })

  });

});
