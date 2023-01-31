#!/usr/bin/env bash
echo "Running composer"
composer install --no-dev --working-dir=/var/www/school-mgt-system-backend-laravel-php

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache


