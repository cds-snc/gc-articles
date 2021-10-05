/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

describe('Track Login Panel', () => {
    beforeEach(() => {
        cy.intercept(
          {
              method: 'GET',
              url: 'index.php?rest_route=/user/logins', // that have a URL that matches '/users/*'
          },
          [
              { "time_login": "2021-09-16 20:55:09", "user_agent": 'Chrome | MacOS' },
              { "time_login": "2021-09-21 18:15:28", "user_agent": 'Chrome | MacOS' },
              { "time_login": "2021-09-21 19:06:07", "user_agent": 'Chrome | MacOS' }
          ]
        ).as('getListCounts');

        cy.login();
    });

    it('Can view Track Login Panel on dashboard', () => {
        cy.visitDashboard();
        cy.screenshot();
        cy.get('#logins-panel-container .login-date').should('have.text', 'Date');
        cy.get('#logins-panel-container .login-userAgent').should('have.text', 'User agent');
        cy.get('#logins-panel-container table tbody').find('tr').should('have.length', 3)
    });
});

describe('Track Login Panel captures logins', () => {
    before(() => {
        cy.exec('npm run wp-env:clean')
    });

    it('On first Login display only one login', () => {
        cy.login();
        cy.visitDashboard();
        cy.screenshot();
        cy.get('#logins-panel-container table tbody').find('tr').should('have.length', 1)
    })

    it('On second Login display two logins', () => {
        cy.login();
        cy.visitDashboard();
        cy.screenshot();
        cy.get('#logins-panel-container table tbody').find('tr').should('have.length', 2)
    })

    it('On third and subsequent Login display three logins', () => {
        cy.login();
        cy.visitDashboard();
        cy.screenshot();
        cy.get('#logins-panel-container table tbody').find('tr').should('have.length', 3)

        cy.login();
        cy.visitDashboard();
        cy.screenshot();
        cy.get('#logins-panel-container table tbody').find('tr').should('have.length', 3)
    })
})
