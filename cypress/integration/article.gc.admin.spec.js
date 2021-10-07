import { addArticle } from "./util";

/// <reference types="Cypress" />

describe('Add Article Admin', () => {
    it('GC Admin can add an article', async () => {
        cy.addUser('gcadmin', 'secret', 'gcadmin');
        cy.login('gcadmin', 'secret');
        const text = "Hello from GC Admin";
        const href = await addArticle(text);
        cy.visit(href);
        cy.get(".entry-content p").contains(text);
    });
});