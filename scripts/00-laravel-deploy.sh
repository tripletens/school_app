#!/usr/bin/env bash
echo "Running composer"
composer global require hirak/prestissimo
composer install --ignore-platform-req --no-dev --working-dir=/var/www

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache


