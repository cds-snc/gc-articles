#!/usr/bin/env bash

cd /usr/src/wordpress
wp multisite-db list
echo ""
echo "To delete these orphaned tables run:"
echo "'articles db-cleanup delete'"