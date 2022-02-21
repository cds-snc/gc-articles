describe('Notify API Settings', () => {
    before(() => {
      cy.testSetup();
    });
  
    beforeEach(() => {
      cy.login();
    });
  
    it('Displays settings screen', () => {
      cy.visit('/wp-admin/options-general.php?page=notify-settings');
  
      cy.get('h1').should('have.text', 'Notify API Settings');
  
      cy.get('#notify_api_key').should('be.empty');
      cy.get('#notify_generic_template_id').should('be.empty');
    });
  
    it('Can save settings', () => {
      cy.visit('/wp-admin/options-general.php?page=notify-settings');
  
      cy.get('#notify_api_key').type('abcdefghijklmnopqrstuvwxyz');
      cy.get('#notify_generic_template_id').type('12345678910111213');
      cy.get('#submit').click();
  
      cy.get('#setting-error-settings_updated').should('contain.text', 'Settings saved');
    })
  
    it('Encrypted settings are not re-populated', () => {
      cy.visit('/wp-admin/options-general.php?page=notify-settings');
  
      cy.get('#notify_api_key').should('be.empty');
      cy.get('#notify_generic_template_id').should('contain.value', '12345678910111213');
    })
});
