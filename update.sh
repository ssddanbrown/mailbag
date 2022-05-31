#!/bin/bash

# This should be executed from the top-level mailbag install folder.
# (Where this script is by default)

php artisan down && \
cp storage/database/database.sqlite ./database-backup.sqlite
git pull origin main && \
composer install --optimize-autoloader --no-dev && \
php artisan migrate --force && \
npm ci && \
npm run build && \
php artisan queue:restart && \
php artisan config:cache && \
php artisan route:cache && \
php artisan view:cache && \
php artisan up
