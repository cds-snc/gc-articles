Cypress.Commands.add('testSetup', ({ theme = 'cds-default' } = {}) => {
  const options = { failOnNonZeroExit: false }

  cy.exec('wp-env run tests-cli wp db import "test_run_dump.sql"', options).then((result) => {
    cy.log(result.code);
    cy.log(result.stdout);

    cy.exec(`wp-env run tests-cli wp theme activate ${theme}`, options)

    if(result.code === 0) {
      return;
    }

    cy.exec('npm run wp-env:clean', options);
    cy.exec('wp-env run tests-cli wp option delete list_values', options);
    cy.exec('wp-env run tests-cli wp option set list_values --format=json < ./cypress/fixtures/notify-list-data.json', options)
    cy.exec('wp-env run tests-cli wp plugin activate sitepress-multilingual-cms cds-base two-factor;', options)
    cy.exec('wp-env run tests-cli wp plugin activate s3-uploads disable-user-login;', options) // wps-hide-login
    cy.exec('wp-env run tests-cli wp plugin activate wordpress-seo wordpress-seo-premium wp-rest-api-v2-menus;', options)
    cy.exec('wp-env run tests-cli wp rewrite structure \'/%postname%/\';')
    cy.exec('wp-env run tests-cli wp set_encrypted_option NOTIFY_API_KEY gc-articles-xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx-xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', options)
    cy.exec('wp-env run tests-cli wp option set NOTIFY_GENERIC_TEMPLATE_ID xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx')
    cy.exec('wp-env run tests-cli "wp option add LIST_MANAGER_NOTIFY_SERVICES \'Les Articles GC Articles~gc-articles-fb26a6b5-57aa-4cc2-85fe-3053ed344fe8-30569ea9-362b-41c4-a811-842ccf3db3dc\'"', options)
    cy.exec('wp-env run tests-cli wp db export "test_run_dump.sql"', options)
  });
});