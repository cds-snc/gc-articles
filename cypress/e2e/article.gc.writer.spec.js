import { addArticle } from "./util";

/// <reference types="Cypress" />

describe('Add Article as GC Writer', () => {
    it('GC Writer can add an article', async () => {
        cy.addUser('gcwriter', 'secret', 'gcwriter');
        cy.login('gcwriter', 'secret');
        const text = "Hello from GC Writer";
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
