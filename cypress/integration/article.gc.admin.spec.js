import { addArticle } from "./util";

/// <reference types="Cypress" />

describe('Add Article Admin', () => {
    it('GC Admin can add an article', async () => {
        cy.addUser('gcadmin', 'secret', 'gcadmin');
        cy.login('gcadmin', 'secret');
        const text = "Hello from GC Admin";
        addArticle(text)

        cy.get('a.editor-post-preview').should($a => {
            expect($a.attr('href'), 'href').to.contain('preview=true')
        }).invoke('attr', 'href').then(href => {
            cy.visit(href);
        });

        cy.get(".entry-content p").contains(text);
        cy.get("meta[name='description']").should("have.attr", "content", text);
    });
});
