/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

const addArticle = async (text) => {
    cy.visit("/wp-admin/post-new.php");
    cy.setPostContent(text);
    cy.wait(1000);
    cy.get("body").type('{cmd}s');
    cy.wait(1000);

    return new Promise((resolve, reject) => {
        cy.get(".editor-post-preview").invoke('attr', 'href').then((href) => {
            cy.visit(href);
            resolve(cy.get(".entry-content").contains(text));
        });
    })


}

describe('Add Article', () => {
    beforeEach(() => {
    });

    it('GC Admin can add an article', async () => {
        cy.addUser('gcadmin', 'secret', 'GC Admin');
        cy.loginUser('gcadmin', 'secret');
        const text = "Hello from GC Editor";
        await addArticle(text);
    });

    it('GC Editor can add an article', async () => {
        cy.addUser('gceditor', 'secret', 'GC Editor');
        cy.loginUser('gceditor', 'secret');
        const text = "Hello from GC Admin";
        await addArticle(text);
    });
});
