#!/usr/bin/env bash

cd /usr/src/wordpress
wp multisite-db list
wp multisite-db delete --force