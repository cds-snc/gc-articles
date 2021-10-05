/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

describe('Notify Template Sender', () => {
    before(() => {
        cy.exec('npm run wp-env:test:setup')
    });

    beforeEach(() => {
        cy.intercept(
          {
              method: 'GET',
              url: 'index.php?rest_route=/wp-notify/v1/list_counts',
          },
          [
              { "list_id": "fb26a6b5-57aa-4cc2-85fe-3053ed344fe8", "subscriber_count": 3 },
              { "list_id": "cae1cb58-8792-4492-b35c-b1595a161ae2", "subscriber_count": 3 },
              { "list_id": "vdf7d370-71c2-48ad-9d59-4e2f7b9b828a", "subscriber_count": 2 }
          ]
        ).as('getListCounts');

        cy.intercept(
          {
              method: 'POST',
              url: 'http://localhost:8889/wp-json/wp-notify/v1/bulk',
          },
          {
              statusCode: 200,
              body: 'it worked!',
          }
        ).as('bulkSender');

        const host = Cypress.config().baseUrl;
        cy.intercept('POST', host + '/wp-json/wp-notify/v1/bulk', (req) => {
            req.redirect("/wp-admin/admin.php?page=cds_notify_send&status=200");
        }).as('bulkSender');

        cy.login();
    });

    it('Send Notify Template', () => {
        cy.visitNotify();
        cy.screenshot();
        cy.get('h1').should('have.text', 'Send Notify Template');

        cy.get('select#list_id').select('One more');
        cy.get('form#notify_template_sender_form').submit();
        cy.get('#swal2-html-container').contains('This list has 0 subscribers');
        cy.get('.swal2-cancel').click();

        cy.get('select#list_id').select('Another List');
        cy.get('form#notify_template_sender_form').submit();
        cy.get('#swal2-html-container').contains('This list has 2 subscribers');
        cy.get('.swal2-cancel').click();

        cy.get('select#list_id').select('My List');
        cy.get('select#list_id option:selected').should('have.value', 'fb26a6b5-57aa-4cc2-85fe-3053ed344fe8~email');
        cy.get('form#notify_template_sender_form').submit();
        cy.get('#swal2-html-container').contains('This list has 3 subscribers');
        cy.get('.swal2-confirm').click();
        cy.wait('@bulkSender');
        cy.get('.notice-sent').should('have.text', 'Sent');
    });
});
