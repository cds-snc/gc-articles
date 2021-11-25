import { addPage } from "./util";

/// <reference types="Cypress" />


describe('Add Side Nav', () => {
    before(() => {
        cy.exec('npm run wp-env:test:setup')
      });

    it('GC Admin can add a child page', async () => {
        const parentPage = {
            title: "Parent page",
            text: "Hello from GC Admin"
        }

        const childPage = {
            title: "Child page",
            text: "Hello again from GC Admin"
        }

        cy.addUser('gcadmin', 'secret', 'administrator');
        cy.login('gcadmin', 'secret');
        addPage(parentPage.text, parentPage.title);

        // Publish the page (instead of preview it)
        cy.get('button.editor-post-publish-panel__toggle').contains('Publish').click();
        cy.get('.editor-post-publish-panel button.editor-post-publish-button__button').contains('Publish').click();
        cy.get(".post-publish-panel__postpublish-header").contains(`${parentPage.title} is now live.`);

        // Add 2nd page
        addPage(childPage.text, childPage.title);
        
        // Open up the settings sidebar and select the parent page
        cy.get('button[aria-label="Settings"]').click();
        cy.get('button[data-label="Page"]').click();
        cy.get('button.components-panel__body-toggle').contains('Page Attributes').click();
        cy.get('label').contains('Parent Page:').invoke('attr', 'for').then(id => {
            cy.get(`#${id}`).click();
            cy.wait(1000);
            cy.get('li[role="option"]').contains(parentPage.title).click();
        });

        // Publish the page (instead of preview it)
        cy.get('button.editor-post-publish-panel__toggle').contains('Publish').click();
        cy.get('.editor-post-publish-panel button.editor-post-publish-button__button').contains('Publish').click();
        cy.get(".post-publish-panel__postpublish-header").contains(`${childPage.title} is now live.`);

        // Visit the newly published page
        cy.get('.post-publish-panel__postpublish-buttons a').contains('View Page').invoke('attr', 'href').then(href => {
            cy.visit(href);
        });

        // Check for H1 and paragraph on child page
        cy.get("h1").contains(childPage.title);
        cy.get(".entry-content p").contains(childPage.text);

        // Check for side nav
        cy.get("#subpages .nav--about__desktop__title").contains(parentPage.title);
        cy.get("#subpages .nav--about li").contains(childPage.title);
    });
});