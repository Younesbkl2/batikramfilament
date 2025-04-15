#!/bin/bash

# Create required directories
mkdir -p /var/www/storage/logs/
touch /var/www/storage/logs/worker.log
touch /var/www/storage/logs/worker-error.log
chown -R www-data:www-data /var/www/storage

# Apply environment variables to Nginx config
envsubst '$PORT' < /etc/nginx/sites-available/laravel.template > /etc/nginx/sites-available/laravel

# Create symlink (if not exists)
if [ ! -f /etc/nginx/sites-enabled/laravel ]; then
    ln -s /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/laravel
fi

# Handle storage link (skip if exists)
if [ ! -L public/storage ]; then
    php artisan storage:link
    chown -R www-data:www-data /var/www/storage/app/public /var/www/public/storage
else
    echo "Storage link already exists, skipping creation"
fi

# Run database migrations (if any)
php artisan migrate --force

mkdir -p /tmp/excel
chmod -R 777 /tmp/excel

# Start services with explicit config path to avoid warning
/usr/bin/supervisord -c /etc/supervisor/supervisord.conf