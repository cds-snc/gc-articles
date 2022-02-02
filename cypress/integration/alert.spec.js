/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

describe('Alert Block', () => {
    before(() => {
        cy.testSetup();
    });
    
    beforeEach(() => {
        cy.login();
        cy.visit('/wp-admin/options-permalink.php');
        cy.visit('/wp-admin/index.php');
        
        cy.createNewPost();
    });

    it('Alert block should be initialized with default attributes', () => {
        cy.insertAlertBlock();
        cy.selectAlertBlock();
    });
});
