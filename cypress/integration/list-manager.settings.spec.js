describe('List Manager Settings', () => {
  before(() => {
    cy.testSetup();
  });

  beforeEach(() => {
    cy.login();
  });

  it.skip('Displays settings screen', () => {
    cy.visit('/wp-admin/admin.php?page=cds_list_manager_settings');

    cy.get('h1').should('have.text', 'List Manager Settings');

    cy.get('.api-key').should('be.empty');
  });

  it.skip('Can save settings', () => {
    cy.visit('/wp-admin/admin.php?page=cds_list_manager_settings');

    // @todo update test
    // cy.get('#list_manager_notify_services').type('listmanagerserviceslist');
    cy.get('#submit').click();

    cy.get('#setting-error-settings_updated').should('contain.text', 'Settings saved');
  })
});
