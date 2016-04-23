#!/bin/bash
cd /var/www
git fetch --all
git reset origin/master --hard
composer install
chmod 777 -R var/*