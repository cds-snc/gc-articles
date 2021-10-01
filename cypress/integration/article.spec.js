/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

const addArticle = (text) => {
    cy.visit("/wp-admin/post-new.php");
    cy.setPostContent(text);
    cy.wait(1000);
    cy.get("body").type('{cmd}s');
    cy.wait(1000);
    cy.get(".editor-post-preview").invoke('attr', 'href').then((href) => {
        cy.visit(href);
        cy.get(".entry-content").contains(text);
    });
}

describe('Add Article', () => {
    beforeEach(() => {
    });

    it('GC Admin can add an article', async () => {
        cy.addUser('gcadmin', 'secret', 'GC Admin');
        cy.loginUser('gcadmin', 'secret');
        addArticle("Hello from GC Admin");
    });

    it('GC Editor can add an article', async () => {
        cy.addUser('gceditor', 'secret', 'GC Editor');
        cy.loginUser('gceditor', 'secret');
        addArticle("Hello from GC Editor");
    });
});
