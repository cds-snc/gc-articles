describe.skip('List Manager Settings', () => {
  before(() => {
    cy.testSetup();
  });

  beforeEach(() => {
    cy.login();
  });

  it('Displays settings screen', () => {
    cy.visit('/wp-admin/admin.php?page=cds_list_manager_settings');

    cy.get('h1').should('have.text', 'List Manager Settings');

    cy.get('.api-key').should('be.empty');
  });

  it('Can save settings', () => {
    cy.visit('/wp-admin/admin.php?page=cds_list_manager_settings');

    // @todo update test
    // cy.get('#list_manager_notify_services').type('listmanagerserviceslist');
    cy.get('#submit').click();

    cy.get('#setting-error-settings_updated').should('contain.text', 'Settings saved');
  })

  it('Encrypted settings are not re-populated', () => {
    cy.visit('/wp-admin/admin.php?page=cds_list_manager_settings');
    
    cy.get('#list_manager_notify_services').should('be.empty');
  })
});