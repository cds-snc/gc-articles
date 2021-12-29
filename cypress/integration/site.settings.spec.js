describe('Site Settings', () => {
  before(() => {
    cy.exec('npm run wp-env:test:setup')
  });

  beforeEach(() => {
    cy.login();
  });

  it('Displays settings screen', () => {
    cy.visit('/wp-admin/options-general.php?page=collection-settings');

    cy.get('h1').should('have.text', 'Collection Settings');

    cy.get('#collection_maintenance').should('be.empty');
    cy.get('#collection_live').should('be.empty');
  });

  it('Can save collection settings and show maintenance pagec', () => {
    cy.visit('/wp-admin/options-general.php?page=collection-settings');
    cy.get('#collection_maintenance').check();
    cy.get('#submit').click();
    cy.get('#setting-error-settings_updated').should('contain.text', 'Settings saved');
    
    cy.get('#wp-admin-bar-my-account > [aria-haspopup="true"]').trigger('mouseover');
    cy.get("a").contains("Log Out").click({force: true});
    cy.visit('/');
    cy.get('h1').should('have.text', "We're currently working on this");
    
  })
});