#!/bin/bash

# Clear runtime directory
rm -rf /var/run/php/*
mkdir -p /var/run/php
chown -R www-data:www-data /var/run/php

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ensure storage directories
mkdir -p /var/www/storage/logs/
chown -R www-data:www-data /var/www/storage

# Apply PORT to Nginx config
envsubst '$PORT' < /etc/nginx/sites-available/laravel.template > /etc/nginx/sites-available/laravel

# Configure Nginx
ln -sf /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/
rm -rf /etc/nginx/sites-enabled/default

# Start PHP-FPM in foreground with debug
echo "Starting PHP-FPM..."
php-fpm -F &
php_pid=$!

# Wait for socket creation with process check
echo "Waiting for PHP-FPM (pid $php_pid)..."
timeout=15
while [ $timeout -gt 0 ]; do
    if ps -p $php_pid > /dev/null && [ -S /var/run/php/php-fpm.sock ]; then
        break
    fi
    sleep 1
    ((timeout--))
done

if [ ! -S /var/run/php/php-fpm.sock ]; then
    echo "CRITICAL: PHP-FPM failed to start!"
    echo "PHP-FPM Log:"
    cat /var/log/php-fpm.log
    exit 1
fi

# Verify socket
echo "PHP-FPM socket verified:"
ls -l /var/run/php/php-fpm.sock

# Start Nginx
echo "Starting Nginx..."
exec nginx -g "daemon off;"