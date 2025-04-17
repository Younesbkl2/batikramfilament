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

# Verify PHP-FPM socket
echo "Checking PHP-FPM socket..."
ls -l /var/run/php-fpm.sock || true

# Start PHP-FPM
php-fpm -D
sleep 2  # Wait for PHP-FPM

# Start Nginx in foreground
echo "Starting Nginx on port ${PORT}..."
nginx -g "daemon off;" &
sleep 3  # Wait for Nginx

# Final verification
echo "Port verification:"
ss -tulpn | grep ":${PORT}" || true

# Keep container running
wait