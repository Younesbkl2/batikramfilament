#!/bin/bash

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ensure runtime directory exists
mkdir -p /var/run/php
chown -R www-data:www-data /var/run/php

# Ensure storage directories
mkdir -p /var/www/storage/logs/
chown -R www-data:www-data /var/www/storage

# Apply PORT to Nginx config
envsubst '$PORT' < /etc/nginx/sites-available/laravel.template > /etc/nginx/sites-available/laravel

# Configure Nginx
ln -sf /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/
rm -rf /etc/nginx/sites-enabled/default

# Start PHP-FPM
echo "Starting PHP-FPM..."
php-fpm -D

# Wait for socket creation with timeout
echo "Waiting for PHP-FPM socket (max 10s)..."
timeout=10
while [ ! -S /var/run/php/php-fpm.sock ] && [ $timeout -gt 0 ]; do
    sleep 1
    ((timeout--))
done

if [ ! -S /var/run/php/php-fpm.sock ]; then
    echo "ERROR: PHP-FPM socket not found after 10 seconds!"
    exit 1
fi

# Verify socket permissions
chown www-data:www-data /var/run/php/php-fpm.sock
chmod 0660 /var/run/php/php-fpm.sock
echo "Socket verified:"
ls -l /var/run/php/php-fpm.sock

# Start Nginx in foreground
echo "Starting Nginx..."
nginx -g "daemon off;" &
nginx_pid=$!

# Verify port binding
echo "Port verification:"
ss -tulpn | grep ":${PORT}" || true

# Keep container running
wait $nginx_pid