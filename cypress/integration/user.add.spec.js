/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

const assertEmailErrors = (cy, message = "Email is required") => {
  cy.get('.components-notice.is-error ul').contains(message);
  cy.get('#validation-error--email').contains(message);
  cy.get('input#email').invoke('attr', 'aria-describedBy').should('eq', 'validation-error--email')
}

const assertRoleErrors = (cy) => {
  cy.get('.components-notice.is-error ul').contains("Role is required.");
  cy.get('#validation-error--role').contains("Role is required.");
  cy.get('select#role').invoke('attr', 'aria-describedBy').should('eq', 'validation-error--role')
}

describe('Find users', () => {
  before(() => {
  });

  after(() => {
  });

  it('Can load the new Add User page', () => {
    cy.login();
    cy.contains('Users').click()
    cy.get('h1').contains("Users"); // get to the "users page"
    
    cy.get('#wpbody .page-title-action').contains('Add New').click()
    cy.get('h1').contains("Add user");
    
    // Get the roles
    cy.get("select#role").select('GC Editor').should('have.value', 'gceditor');
    cy.get("select#role").select('GC Admin').should('have.value', 'gcadmin');

    cy.get(".components-button.is-primary").first().should('have.text', "Add user");
  });

  it('Gets correct validation messages for no email and no role', () => {
    cy.login();
    cy.visit("wp-admin/users.php?page=users-add");
    cy.contains('button', 'Add user').click()

    // error summary
    cy.get('h2').contains("There is a problem");
    assertEmailErrors(cy)
    assertRoleErrors(cy)
  });

  it('Gets correct validation messages for good email and no role', () => {
    cy.login();
    cy.visit("wp-admin/users.php?page=users-add");
    cy.get('input#email').type("editor@cds-snc.ca")
    cy.contains('button', 'Add user').click()

    // error summary
    cy.get('h2').contains("There is a problem");
    assertRoleErrors(cy)
  });

  it('Gets correct validation messages for bad email domain', () => {
    cy.login();
    cy.visit("wp-admin/users.php?page=users-add");
    cy.get('input#email').type("editor@gmail.com") // domain is not allowed
    cy.get('select#role').select('gceditor')
    cy.contains('button', 'Add user').click()

    // error summary
    cy.get('h2').contains("There is a problem");
    assertEmailErrors(cy, "You can’t use this email domain for registration.")
  });

  it.skip('Successfully adds a new user', () => {
    cy.login();
    cy.visit("wp-admin/users.php?page=users-add");
    cy.get('input#email').type("editor@cds-snc.ca") // domain is not allowed
    cy.get('select#role').select('gceditor')
    cy.contains('button', 'Add user').click()

    // error summary
    cy.get('h2').contains("Success!");
    assertEmailErrors(cy, "You can’t use this email domain for registration.")
  });
});
