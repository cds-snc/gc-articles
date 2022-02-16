/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

describe('Two Factor Panel', () => {
    beforeEach(() => {
        cy.login();
    });

    after(() => {

    });

    it('Can view Two Factor Panel on dashboard', () => {
        cy.visitDashboard();

        cy.get('#cds_2fa_widget').should('be.visible');
    });

    it('Dashboard panel is hidden when 2fa configured', () =>{
        cy.visitProfile();

        cy.get('[type="checkbox"]').check('Two_Factor_Email');
        cy.get('#submit').click();
        cy.visitDashboard();

        cy.get('#cds_2fa_widget').should('not.exist');
    });
});
