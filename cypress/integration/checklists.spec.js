import { addChecklistRule, addArticle } from "./util";

/// <reference types="Cypress" />

const addChecklistRules = () => {
  cy.visit('/wp-admin/admin.php?page=ppch-checklists');

  cy.get('h1').should('have.text', 'Checklists');

  addChecklistRule({required: true});
  addChecklistRule({required: false});
  
  cy.get('#submit').click();
}

describe('Checklists', () => {
  before(() => {
    cy.testSetup();
    cy.login();
    addChecklistRules();
  });

  beforeEach(() => {
    cy.login();
  });

  it('Prevent modal appears → Don’t publish', () => {
    const article = {
      text: "Hello from GC Admin",
      title: "New post title"
    }

    addArticle(article.text, {title: article.title})

    cy.get('#ppc-publish').click()

    // check "prevent" modal is visible
    cy.get('.ppc-modal-prevent').should('be.visible')
    // check "prevent" modal is focused
    cy.focused().should('have.attr', 'class', 'ppc-popup-prevent')

    // make sure the button is there
    cy.get('.ppc-modal-prevent').find('button').should('have.attr', 'class', 'ppc-popup-option-okay')
  });

  it('Warning modal appears → Don’t publish', () => {
    const article = {
      text: "Hello from GC Admin 2",
      title: "New post title 2"
    }

    addArticle(article.text, {title: article.title})

    // Open "Settings" panel if it is closed
    cy.get('.edit-post-header__settings').then($panel => {
      if($panel.find('button[aria-label="Settings"][aria-expanded="false"]')) {
        $panel.find('button[aria-label="Settings"]').trigger('click');
      }
    })

    // All "required" items
    cy.get('.pp-checklists-block.status-no').each(($el) => {
      $el.find('input').trigger('click');
    })

    cy.get('#ppc-publish').click()

    // check "warn" modal is visible
    cy.get('.ppc-modal-warn').should('be.visible')
    // check "warn" modal is focused
    cy.focused().should('have.attr', 'class', 'ppc-popup-warn')

    // check for the "don't publish" button, and click it
    cy.get('.ppc-modal-warn').find('button.ppc-popup-option-dontpublish').should('have.length', 1).click()

    // "Save draft" button still exists
    cy.get('.editor-post-save-draft').should('be.visible').should('have.text', 'Save draft')
    // Custom "publish..." button still exists
    cy.get('#ppc-publish').should('be.visible').should('have.text', `Publish…`)
  });

  it('Warning modal appears → Publish anyway', () => {
    const article = {
      text: "Hello from GC Admin 3",
      title: "New post title 3"
    }

    addArticle(article.text, {title: article.title})

    // Open "Settings" panel if it is closed
    cy.get('.edit-post-header__settings').then($panel => {
      if($panel.find('button[aria-label="Settings"][aria-expanded="false"]')) {
        $panel.find('button[aria-label="Settings"]').trigger('click');
      }
    })

    // All "required" items
    cy.get('.pp-checklists-block.status-no').each(($el) => {
      $el.find('input').trigger('click');
    })

    cy.get('#ppc-publish').click()

    // check "warn" modal is visible
    cy.get('.ppc-modal-warn').should('be.visible')
    // check "warn" modal is focused
    cy.focused().should('have.attr', 'class', 'ppc-popup-warn')

    // check for the "publish anyway" button, and click it
    cy.get('.ppc-modal-warn').find('button.ppc-popup-options-publishanyway').should('have.length', 1).click()

    // Close the "post-publish" panel
    cy.get('.post-publish-panel__postpublish-subheader', { timeout: 3000 }).should('be.visible').then($el => {
      // For unknown reasons, an "s" appears in this string in CI but not locally
      cy.get('.post-publish-panel__postpublish-header.is-opened').contains(/New post title \d[s]? is now live/)
      cy.get('.editor-post-publish-panel__header button[aria-label="Close panel"]').first().trigger('click')
    })

    // check custom "update" button is visible
    cy.get('#ppc-update').should('be.visible').should('have.text', `Update…`)
  });

  it('No modal appears → Publish', () => {
    const article = {
      text: "Hello from GC Admin 4",
      title: "New post title 4"
    }

    addArticle(article.text, {title: article.title})

    // check that custom "publish" button appears initially
    cy.get('#ppc-publish').should('be.visible').should('have.text', 'Publish…')
    // Check that default Publish button is not visible
    cy.get('.editor-post-publish-panel__toggle').should('not.be.visible')

    // Open "Settings" panel if it is closed
    cy.get('.edit-post-header__settings').then($panel => {
      if($panel.find('button[aria-label="Settings"][aria-expanded="false"]')) {
        $panel.find('button[aria-label="Settings"]').trigger('click');
      }
    })

    // All checklist items
    cy.get('.pp-checklists-req.status-no').each(($el) => {
      $el.find('input').trigger('click');
    })

    // check that custom "publish" button is no longer visible
    cy.get('#ppc-publish').should('not.be.visible')
    // Check that default Publish button is visible, and click it
    cy.get('.editor-post-publish-panel__toggle').should('be.visible').should('have.text', `Publish`).click()
    // Click the slideout "Publish" button
    cy.get('.editor-post-publish-panel .editor-post-publish-button').should('be.visible').should('have.text', `Publish`).click()

    // Close the "post-publish" panel
    cy.get('.post-publish-panel__postpublish-subheader', { timeout: 3000 }).should('be.visible').then($el => {
      // For unknown reasons, an "s" appears in this string in CI but not locally
      cy.get('.post-publish-panel__postpublish-header.is-opened').contains(/New post title \d[s]? is now live/)
      cy.get('.editor-post-publish-panel__header button[aria-label="Close panel"]').first().trigger('click')
    })

    // check default "Update" button is visible
    cy.get('.editor-post-publish-button').should('be.visible').should('have.text', `Update`)
  });
});
