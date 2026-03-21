#!/bin/bash

DB_BACKUP=test_run_dump.sql

echo "Looking for $DB_BACKUP"
if wp-env run tests-cli wp db import "$DB_BACKUP"; then
    echo "Found $DB_BACKUP and imported it"
    exit 0
fi
echo "Didn't find $DB_BACKUP will save one for next time"

wp-env clean
wp-env run tests-cli wp option delete list_values
wp-env run tests-cli wp option set list_values --format=json < ./cypress/fixtures/notify-list-data.json
wp-env run tests-cli wp theme activate cds-default
wp-env run tests-cli wp plugin activate sitepress-multilingual-cms cds-base two-factor;
wp-env run tests-cli wp plugin activate s3-uploads disable-user-login; # wps-hide-login
wp-env run tests-cli wp plugin activate wp-rest-api-v2-menus;

wp-env run tests-cli wp rewrite structure "/%postname%/";
# wp-env run tests-cli wp db export "$DB_BACKUP"
