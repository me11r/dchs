#!/usr/bin/env bash
composer i --ignore-platform-reqs --optimize-autoloader --no-suggest --no-progress
npm ci
npm run prod
php ./vendor/bin/dep deploy.php "$1"
