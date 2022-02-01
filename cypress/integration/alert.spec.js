/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

describe('Alert Block', () => {
    before(() => {
        cy.exec('npm run wp-env:test:setup', {
            timeout: 20000
        }).then((result) => {
            cy.log("huzzah");
            cy.log(result.stdout);
        })

    });
    
    beforeEach(() => {
        cy.login();
        cy.createNewPost();
    });

    it('Alert block should be initialized with default attributes', () => {
        cy.insertAlertBlock();
        cy.selectAlertBlock();
    });
});
