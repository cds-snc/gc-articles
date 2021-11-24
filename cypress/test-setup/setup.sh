#!/bin/bash
wp-env run tests-cli wp option delete list_values
wp-env run tests-cli wp option set list_values --format=json < ./cypress/fixtures/notify-list-data.json

wp-env run tests-cli wp theme activate cds-default
wp-env run tests-cli wp option update permalink_structure "/%postname%/";
wp-env run tests-cli wp plugin activate sitepress-multilingual-cms cds-base two-factor;
wp-env run tests-cli wp plugin activate s3-uploads wps-hide-login disable-user-login;

#wp-env run cli wp theme activate cds-default
#wp-env run cli wp option update permalink_structure "/%postname%/";
#wp-env run cli wp plugin activate sitepress-multilingual-cms cds-base two-factor;
#wp-env run cli wp plugin activate s3-uploads wps-hide-login disable-user-login;