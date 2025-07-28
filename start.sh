#!/bin/bash

# Create .env file if it doesn't exist
if [ ! -f /var/www/.env ]; then
    cp /var/www/.env.example /var/www/.env
fi

# Create database directory if it doesn't exist
mkdir -p /var/www/database

# Create SQLite database file if it doesn't exist
if [ ! -f /var/www/database/database.sqlite ]; then
    touch /var/www/database/database.sqlite
    chmod 664 /var/www/database/database.sqlite
    chown www-data:www-data /var/www/database/database.sqlite
fi

# Set proper permissions
chown -R www-data:www-data /var/www
chmod -R 755 /var/www/storage
chmod -R 755 /var/www/bootstrap/cache

# Generate application key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    php artisan key:generate --force
fi

# Run migrations
php artisan migrate --force

# Start nginx
service nginx start

# Start php-fpm
php-fpm -D

# Wait a moment for services to start
sleep 5

# Keep container running
tail -f /dev/null 