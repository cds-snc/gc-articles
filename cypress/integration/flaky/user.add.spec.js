/// <reference types="Cypress" />

const NEW_TAB_REL_DEFAULT_VALUE = 'noreferrer noopener';

const assertEmailErrors = (cy, message = "Email is required") => {
  cy.get('.components-notice.is-error ul').contains(message);
  cy.get('#validation-error--email').contains(message);
  cy.get('input#email').invoke('attr', 'aria-describedBy').should('eq', 'validation-error--email');
}

const assertRoleErrors = (cy) => {
  cy.get('.components-notice.is-error ul').contains("Role is required.");
  cy.get('#validation-error--role').contains("Role is required.");
  cy.get('select#role').invoke('attr', 'aria-describedBy').should('eq', 'validation-error--role');
}

describe('Add user', () => {
  before(() => {
    cy.testSetup();;
  });

  after(() => { });

  beforeEach(() => {

    cy.intercept(
      {
        method: 'GET',
        url: '/wp-json/users/v1/roles',
      },
      [
        {
            "id": "gceditor",
            "name": "GC Editor",
            "description": "GC editor desc"
        },
        {
            "id": "administrator",
            "name": "GC Admin",
            "description": "GC admin desc"
        }
    ]
    ).as('getRoleDescriptions');

    cy.login();
    cy.visit("wp-admin/users.php?page=users-add");
  });

  it('Can load the new Add User page', () => {
    cy.visit("wp-admin");

    cy.contains('Users').click();
    cy.get('h1').contains("Users"); // get to the "users page"

    cy.get('#wpbody .page-title-action').contains('Add New').click();
    cy.get('h1').contains("Add user");

    // Get the roles
    cy.get("select#role").select('GC Editor').should('have.value', 'gceditor');
    cy.get('.role-desc').should('contain', 'GC editor desc');

    cy.get("select#role").select('GC Admin').should('have.value', 'administrator');
    cy.get('.role-desc').should('contain', 'GC admin desc');

    cy.get(".components-button.is-primary").first().should('have.text', "Add user");
  });

  it.skip('Gets correct validation messages for no email and no role', () => {
    cy.contains('button', 'Add user').click();

    // error summary
    cy.get('h2').contains("There is a problem");
    cy.focused().should('contain', 'There is a problem');
    assertEmailErrors(cy)
    assertRoleErrors(cy)
  });

  it.skip('Gets correct validation messages for good email and no role', () => {
    cy.get('input#email').type("editor@cds-snc.ca");
    cy.contains('button', 'Add user').click();

    // error summary
    cy.get('h2').contains("There is a problem");
    cy.focused().should('contain', 'There is a problem');
    assertRoleErrors(cy)
  });

  it.skip('Gets correct validation messages for bad email domain', () => {
    cy.get('input#email').type("editor@gmail.com"); // domain is not allowed
    cy.get('select#role').select('gceditor');
    cy.contains('button', 'Add user').click();

    // error summary
    cy.get('h2').contains("There is a problem");
    cy.focused().should('contain', 'There is a problem');
    assertEmailErrors(cy, "You must enter a Government of Canada email to send an invitation.");
  });

  it.skip('Successfully adds a new user', () => {
    cy.get('input#email').type("new+editor@cds-snc.ca"); // domain is not allowed
    cy.get('select#role').select('gceditor');
    cy.contains('button', 'Add user').click();

    // Success notice
    cy.get('h2').contains("Success!");
    cy.focused().should('contain', 'Success!');

    // make sure exists in username column
    cy.contains('Users').click();
    cy.get('h1').contains("Users");
    cy.get('table.users td.column-username').contains("new+editor@cds-snc.ca");
  });

  it.skip('Shows an error when trying to add an existing user', () => {
    cy.get('input#email').type("new+editor@cds-snc.ca");
    cy.get('select#role').select('gceditor');
    cy.contains('button', 'Add user').click();

    // error summary
    cy.get('h2').contains("There is a problem");
    cy.focused().should('contain', 'There is a problem');
    cy.get('.components-notice.is-error ul').contains("new+editor@cds-snc.ca is already a member of this Collection");
  });
});

describe.skip('As GC Admin', () => {
  before(() => {
    cy.addUser('gcadmin', 'secret', 'administrator');
  });

  it('can add a user', () => {
    cy.login('gcadmin', 'secret');

    cy.visit("wp-admin/users.php");
    cy.get('h1').contains("Users");

    cy.get('#wpbody .page-title-action').contains('Add New').click();
    cy.get('h1').contains("Add user");

    cy.get('input#email').type("editor+2@cds-snc.ca");
    cy.get('select#role').select('gceditor');

    cy.contains('button', 'Add user').click();

    // Success notice
    cy.get('h2').contains("Success!");
    cy.focused().should('contain', 'Success!');
  });
});
