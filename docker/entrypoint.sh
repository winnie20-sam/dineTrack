#!/bin/bash

echo "Waiting for database..."
sleep 10

echo "Running migrations..."
php artisan migrate --force

echo "Running seeders..."
php artisan db:seed --class=StatusSeeder --force
php artisan db:seed --class=RoleSeeder --force

echo "Caching config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting Nginx and PHP-FPM..."
service nginx start
php-fpm
