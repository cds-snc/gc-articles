/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

describe('Alert Block', () => {
    beforeEach(() => {
        cy.loginUser('admin', 'password');
        cy.screenshot();
        cy.createNewPost();
        cy.screenshot();
    });

    it('Alert block should be initialized with default attributes', () => {
        cy.insertAlertBlock();
        cy.screenshot();
        cy.selectAlertBlock();
        cy.screenshot();
    });
});
