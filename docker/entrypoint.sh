#!/bin/bash

echo "Waiting for database..."
sleep 10

echo "Running migrations..."
php artisan migrate --force

echo "Running seeders..."
php artisan db:seed --force

echo "Caching config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting PHP-FPM..."
php-fpm
