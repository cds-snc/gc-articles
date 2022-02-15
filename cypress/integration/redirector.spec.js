import { addArticle } from "./util";

/// <reference types="Cypress" />

describe.skip('Switch theme', () => {
    before(() => {
      cy.testSetup({theme: 'cds-redirector'});
    });

    it('CDS Redirector theme', async () => {
      const redirectUrl = "https://example.com"

      cy.login();

      // set redirect url
      cy.visit("/wp-admin/admin.php?page=theme-settings");
      cy.get('input[name*="redirect_url"]').type(redirectUrl);
      cy.get('input#submit').click();
      cy.get('input[name*="redirect_url"]').should('have.value', redirectUrl);

      // create post
      const text = "Hello from GC Admin";
      addArticle(text)

      // preview > check URL
      cy.get('a.editor-post-preview').should($a => {
        expect($a.attr('href'), 'href').to.contain('preview=true')
      }).invoke('attr', 'href').then(href => {
        cy.request({
          url: href,
          followRedirect: false,
        }).then(resp => {
          expect(resp.status).to.eq(302)
          expect(resp.redirectedToUrl).to.eq("https://example.com/?lang=en")
        })
      });
    });
});
