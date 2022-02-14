/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

describe.skip('User - GC Editor', () => {
    before(() => {
        cy.testSetup();
    });

    after(() => {

    });

    it('GC Admin can add GC Editors', () => {
        cy.addUser('gcadmin', 'secret', 'administrator');
        cy.login('gcadmin', 'secret');
        // try adding a GC Editor using GC Admin account
        cy.addUser('gceditor', 'secret', 'gceditor', false);
    });

});