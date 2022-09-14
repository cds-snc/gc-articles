describe('Site Settings', () => {
  before(() => {
    cy.testSetup();
  });

  beforeEach(() => {
    cy.login();
  });

  it('Displays settings screen', () => {
    cy.visit('/wp-admin/options-general.php?page=collection-settings');

    cy.get('h1').should('have.text', 'Site Settings');

    cy.get('#collection_maintenance').should('be.empty');
    cy.get('#collection_live').should('be.empty');
  });

  it.skip('Changes FIP link', () => {
    cy.visit('/wp-admin/options-general.php?page=collection-settings');

    cy.get('h1').should('have.text', 'Site Settings');

    cy.get('input#fip_href').clear().type("canada.ca/en.html");
    cy.get('#submit').click();
    cy.get('#setting-error-settings_updated').should('contain.text', 'Settings saved');

    cy.visit('/');

    cy.get('header .brand a').should("have.attr", "href", "https://canada.ca/en.html");
  });

  it.skip('Can save collection settings and show maintenance page', () => {
    cy.visit('/wp-admin/options-general.php?page=collection-settings');
    cy.get('#collection_maintenance').check();
    cy.get('#submit').click();
    cy.get('#setting-error-settings_updated').should('contain.text', 'Settings saved');
    
    // log out
    cy.get("#wp-admin-bar-logout > a").should($a => {
      expect($a.attr('href'), 'href').to.contain('action=logout')
    }).invoke('attr', 'href').then(href => {
      cy.visit(href)
    });
    
    // should be logged out
    cy.visit('/')
    cy.get('h1').should('have.text', "We’re currently working on this");
  })
});
