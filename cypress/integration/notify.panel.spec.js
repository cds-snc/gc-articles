/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

describe('Notify Panel', () => {
    before(() => {
        cy.exec('npm run wp-env:test:setup')
    });

    beforeEach(() => {
        cy.intercept(
            {
                method: 'GET',
                url: '/wp-json/wp-notify/v1/list_counts',
            },
            [
                { "list_id": "fb26a6b5-57aa-4cc2-85fe-3053ed344fe8", "subscriber_count": 3 },
                { "list_id": "cae1cb58-8792-4492-b35c-b1595a161ae2", "subscriber_count": 3 },
                { "list_id": "vdf7d370-71c2-48ad-9d59-4e2f7b9b828a", "subscriber_count": 2 }
            ]
        ).as('getListCounts');

        cy.login();
    });

    it('Can view Notify Panel on dashboard', () => {
        cy.visitDashboard();
        cy.screenshot();
        cy.get('#notify-panel-container a').should('have.text', 'Send Template');

        cy.get('.label-my-list').should('have.text', 'My List');
        cy.get('.subscriber-count-my-list').should('have.text', '3');

        cy.get('.label-another-list').should('have.text', 'Another List');
        cy.get('.subscriber-count-another-list').should('have.text', '2');

        cy.get('.subscriber-count-one-more').should('have.text', '0');
    });
});
