#!/bin/bash

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ensure storage directories exist
mkdir -p /var/www/storage/logs/
touch /var/www/storage/logs/worker.log
touch /var/www/storage/logs/worker-error.log
chown -R www-data:www-data /var/www/storage

# Apply PORT to Nginx config
envsubst '$PORT' < /etc/nginx/sites-available/laravel.template > /etc/nginx/sites-available/laravel

# Create symlink for Nginx config
ln -sf /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/

# Create storage link if missing
if [ ! -L public/storage ]; then
    php artisan storage:link
    chown -R www-data:www-data /var/www/storage/app/public /var/www/public/storage
fi

# Run migrations
php artisan migrate --force

# Start PHP-FPM (as daemon)
php-fpm -D

# Start Supervisor
supervisord -c /etc/supervisor/supervisord.conf

# Start Nginx in foreground
nginx -g "daemon off;"