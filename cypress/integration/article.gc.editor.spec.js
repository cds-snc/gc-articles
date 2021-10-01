import { addArticle } from "./util";

/// <reference types="Cypress" />

describe('Add Article as GC Editor', () => {
    it('GC Editor can add an article', async () => {
        cy.addUser('gceditor', 'secret', 'GC Editor');
        cy.loginUser('gceditor', 'secret');
        const text = "Hello from GC Editor";
        const href = await addArticle(text);
        cy.visit(href);
        cy.get(".entry-content p").contains(text);
    });
});

