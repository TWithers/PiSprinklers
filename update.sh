#!/bin/bash
cd /var/www
git fetch --all
git reset origin/master --hard
composer install
php bin/console cache:clear --env=prod
chmod 777 -R var/*