#!/bin/bash

echo "Waiting for database..."
until php artisan migrate --force 2>/dev/null; do
    echo "DB not ready, retrying in 3s..."
    sleep 3
done

echo "Running seeders..."
php artisan db:seed --force

echo "Publishing AdminLTE assets..."
php artisan adminlte:install --only=assets

echo "Caching config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting services..."
service nginx start
php-fpm
