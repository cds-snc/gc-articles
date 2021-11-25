/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

describe('Alert Block', () => {
    beforeEach(() => {
        cy.login();
        cy.createNewPost();
    });

    it.skip('Alert block should be initialized with default attributes', () => {
        cy.insertAlertBlock();

        cy.selectAlertBlock();

    });
});
