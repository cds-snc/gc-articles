import { addArticle } from "./util";

/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

describe.skip('Alert Block', () => {
    before(() => {
        cy.testSetup();
    });

    it('Alert block should be initialized with default attributes', () => {
        cy.addUser('gcadmin', 'secret', 'administrator');
        cy.login('gcadmin', 'secret');

        const text = "Hello from GC Admin";
        addArticle(text)

        cy.searchForBlock('Alert');
        cy.get(
            'button.editor-block-list-item-cds-snc-alert'
        ).click({ force: true });
        cy.selectBlockByName('cds-snc/alert', 0);

    });
});
