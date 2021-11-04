describe('Encrypted options', () => {
  before(() => {
    cy.exec('npm run wp-env:test:setup')
  });

  beforeEach(() => {
    cy.login();
  });

  it('Displays settings screen', () => {
    cy.visit('/wp-admin/admin.php?page=cds_notify_send_settings');

    cy.get('h1').should('have.text', 'Notify and List Manager Settings');

    cy.get('#notify_api_key').should('be.empty');
    cy.get('#notify_generic_template_id').should('be.empty');
    cy.get('#list_manager_api_key').should('be.empty');
    cy.get('#list_manager_notify_services').should('be.empty');
    cy.get('#list_manager_service_id').should('be.empty');
  });

  it('Can save settings', () => {
    cy.visit('/wp-admin/admin.php?page=cds_notify_send_settings');

    cy.get('#notify_api_key').type('abcdefghijklmnopqrstuvwxyz');
    cy.get('#notify_generic_template_id').type('12345678910111213');
    cy.get('#list_manager_api_key').type('thisisthelistmanagerapikey');
    cy.get('#list_manager_notify_services').type('listmanagerserviceslist');
    cy.get('#list_manager_service_id').type('serviceidforlistmanager');
    cy.get('#submit').click();

    cy.get('#setting-error-settings_updated').should('contain.text', 'Settings saved');
  })

  it('Encrypted settings are not re-populated', () => {
    cy.visit('/wp-admin/admin.php?page=cds_notify_send_settings');

    cy.get('#notify_api_key').should('be.empty');
    cy.get('#notify_generic_template_id').should('contain.value', '12345678910111213');
    cy.get('#list_manager_api_key').should('be.empty');
    cy.get('#list_manager_notify_services').should('be.empty');
    cy.get('#list_manager_service_id').should('be.empty');
  })
});