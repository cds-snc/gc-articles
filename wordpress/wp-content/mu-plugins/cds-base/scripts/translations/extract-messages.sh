#!/bin/bash

HERE=`dirname $0`
ROOT="$HERE/../.."
DOCKER_ROOT="/var/www/html/wp-content/plugins/cds-base"

# Create non-minified build to be able to extract labels
NODE_ENV=development npm run build

npm run wp-env run cli "wp i18n make-pot --domain=cds-snc --exclude=\"e2e-tests,phpunit,release,src/*.js\" $DOCKER_ROOT $DOCKER_ROOT/languages/cds-snc.pot"

npm run build
