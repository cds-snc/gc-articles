/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

describe('User Collections Panel', () => {
    beforeEach(() => {
        cy.intercept(
            {
                method: 'GET',
                url: 'index.php?rest_route=/user-collection/collections', // that have a URL that matches '/users/*'
            },
            {
                "1": {
                    "userblog_id": 1,
                    "blogname": "CDS Wordpress Base",
                    "domain": "localhost",
                    "path": "\/",
                    "site_id": 1,
                    "siteurl": "http:\/\/localhost",
                    "archived": "0",
                    "mature": "0",
                    "spam": "0",
                    "deleted": "0"
                },
                "2": {
                    "userblog_id": 2,
                    "blogname": "test",
                    "domain": "localhost",
                    "path": "\/test\/",
                    "site_id": 1,
                    "siteurl": "http:\/\/localhost\/test",
                    "archived": "0",
                    "mature": "0",
                    "spam": "0",
                    "deleted": "0"
                }
            }
        ).as('getUserCollections');

        cy.login();
    });

    it('Can view User Collections Panel on dashboard', () => {
        cy.visitDashboard();
        cy.screenshot();
        cy.get('#collection-panel-container .collection-name').should('have.text', 'Name');
        cy.get('#collection-panel-container .collection-website').should('have.text', 'Website');
        cy.get('#collection-panel-container .collection-admin').should('have.text', 'Dashboard');

        cy.get('#collection-panel-container table tbody').find('tr').should('have.length', 2);
        cy.get('#collection-panel-container .row-1 .name').should('have.text', "test");
        cy.get('#collection-panel-container .row-1 .website a').should('have.text', "http://localhost/test");

    });
});