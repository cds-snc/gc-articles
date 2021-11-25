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
wp-env run tests-cli wp option update permalink_structure "/%postname%/";

wp-env run tests-cli wp plugin activate cds-base

wp-env run tests-cli "wp option add LIST_MANAGER_NOTIFY_SERVICES 'Les Articles GC Articles~gc-articles-fb26a6b5-57aa-4cc2-85fe-3053ed344fe8-30569ea9-362b-41c4-a811-842ccf3db3dc'"

wp-env run tests-cli wp db export "$DB_BACKUP"
