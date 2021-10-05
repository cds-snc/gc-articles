#!/bin/bash
wp-env run tests-cli wp option delete list_values
wp-env run tests-cli wp option set list_values --format=json < ./cypress/fixtures/notify-list-data.json
wp-env run tests-cli wp theme activate cds-default
wp-env run cli wp theme activate cds-default
wp-env run cli wp option update permalink_structure "/%postname%/";