#!/bin/bash

php artisan down && \
git pull origin master && \
composer install --optimize-autoloader --no-dev && \
php artisan migrate && \
npm install && \
npm run build && \
php artisan config:cache && \
php artisan route:cache && \
php artisan view:cache && \
php artisan up
