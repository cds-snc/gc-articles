/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

describe('User Collections Panel', () => {
    beforeEach(() => {
        cy.intercept(
            {
                method: 'GET',
                url: '/wp-json/usercollection/collections',
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
                    "blogname": "Example Site",
                    "domain": "localhost",
                    "path": "\/example\/",
                    "site_id": 1,
                    "siteurl": "http:\/\/localhost\/example",
                    "archived": "0",
                    "mature": "0",
                    "spam": "0",
                    "deleted": "0"
                }
            }
        ).as('getUserCollections');
        cy.login();
    });

    it.skip('Can view User Collections Panel on dashboard', () => {
        cy.visitDashboard();
        cy.get('#collection-panel-container .collection-name').scrollIntoView() 
        cy.get('#collection-panel-container .collection-name').should('have.text', 'Name');
        cy.get('#collection-panel-container .collection-website').should('have.text', 'Website');
        cy.get('#collection-panel-container .collection-admin').should('have.text', 'Admin');

        cy.get('#collection-panel-container table tbody').find('tr').should('have.length', 2);
        cy.get('#collection-panel-container .row-1 .name').should('have.text', "Example Site");
        cy.get("#collection-panel-container .row-1 .website a").should("have.text", "Visit");
        cy.get("#collection-panel-container .row-1 .website a").should("have.attr", "href", "http://localhost/example");
        cy.get("#collection-panel-container .row-1 .admin a").should("have.text", "Dashboard");
        cy.get("#collection-panel-container .row-1 .admin a").should("have.attr", "href", "//localhost/example/wp-admin");
    });
});