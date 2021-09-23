Cypress.Commands.add('selectAlertBlock', (index = 0) => {
    cy.selectBlockByName('cds-snc/alert', index);
});