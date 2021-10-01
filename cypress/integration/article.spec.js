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
            resolve(href);
        });
    })


}

describe('Add Article Admin', () => {
    beforeEach(() => {
    });

    it('GC Admin can add an article', async () => {
        cy.addUser('gcadmin', 'secret', 'GC Admin');
        cy.loginUser('gcadmin', 'secret');
        const text = "Hello from GC Admin";
        const href = await addArticle(text);
        cy.visit(href);
        cy.get(".entry-content p").contains(text);
    });
});

describe('Add Article as GC Editor', () => {
    beforeEach(() => {
    });

    it('GC Editor can add an article', async () => {
        cy.addUser('gceditor', 'secret', 'GC Editor');
        cy.loginUser('gceditor', 'secret');
        const text = "Hello from GC Editor";
        const href = await addArticle(text);
        cy.visit(href);
        cy.get(".entry-content p").contains(text);
    });
});

