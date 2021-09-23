Cypress.Commands.add('visitNotify', () => {
    cy.visit("/wp-admin/admin.php?page=cds_notify_send");
});

