#!/usr/bin/env bash

FUNCTIONS_FILE=/tmp/wordpress-tests-lib/includes/functions.php

cd /usr/src/wordpress/wp-content/plugins/gc-lists

if [[ ! -f $FUNCTIONS_FILE ]]; then
    echo "[GC Lists] One moment, need to set a few things up..."
    ./bin/install-wp-tests.sh wordpress_testing root secret db > /dev/null 2>&1
fi

./vendor/bin/pest