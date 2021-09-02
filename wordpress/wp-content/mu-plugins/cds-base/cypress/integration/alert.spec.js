/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

describe('Alert Block', () => {
    beforeEach(() => {
        cy.loginUser();
        cy.createNewPost();
    });

    it('Alert block should be initialized with default attributes', () => {
        cy.insertAlertBlock();
        cy.selectAlertBlock();
    });

    
});
