/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

describe('User - GC Editor', () => {
    before(() => {
        cy.testSetup();
    });

    it('GC Admin can add GC Editors', () => {
        cy.addUser('gcadmin', 'secret', 'administrator');
        cy.login('gcadmin', 'secret');
        // try adding a GC Editor using GC Admin account
        cy.addUser('gceditor', 'secret', 'gceditor', false);
    });

}); 
