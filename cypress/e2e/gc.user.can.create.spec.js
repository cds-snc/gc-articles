/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

describe('User - GC Editor & GC Writer', () => {
    before(() => {
        cy.testSetup();
    });

    after(() => {

    });

    it('GC Admin can add GC Editors & GC Writers', () => {
        cy.addUser('gcadmin', 'secret', 'administrator');
        cy.login('gcadmin', 'secret');
        // try adding a GC Editor using GC Admin account
        cy.addUser('gceditor', 'secret', 'gceditor', false);
        // try adding a GC Editor using GC Writer account
        cy.addUser('gcwriter', 'secret', 'writer', false);
    });

}); 
