#!/bin/bash

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

# Start PHP-FPM with explicit socket directory
php-fpm -D

# Wait for socket creation
echo "Waiting for PHP-FPM socket..."
while [ ! -S /var/run/php/php-fpm.sock ]; do
    sleep 1
done
chown www-data:www-data /var/run/php/php-fpm.sock
chmod 0660 /var/run/php/php-fpm.sock

# Start Nginx in foreground
echo "Starting Nginx..."
exec nginx -g "daemon off;"

# Final verification
echo "Port verification:"
ss -tulpn | grep ":${PORT}" || true

# Keep container running
wait