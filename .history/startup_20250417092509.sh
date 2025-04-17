#!/bin/bash

# Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ensure storage directories exist
mkdir -p /var/www/storage/logs/
chown -R www-data:www-data /var/www/storage

# Apply PORT to Nginx config
envsubst '$PORT' < /etc/nginx/sites-available/laravel.template > /etc/nginx/sites-available/laravel

# After applying envsubst
echo "Final Nginx configuration:"
cat /etc/nginx/sites-available/laravel

# Create symlink for Nginx config
ln -sf /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/

# Create storage link if missing
if [ ! -L public/storage ]; then
    php artisan storage:link
    chown -R www-data:www-data /var/www/storage/app/public /var/www/public/storage
fi

# Run migrations
php artisan migrate --force

# Verify port configuration
echo "Nginx will bind to:"
grep 'listen' /etc/nginx/sites-available/laravel

echo "Verifying port binding:"
ss -tulpn | grep ":${PORT}" || true

# Start services
# Start services in correct order
php-fpm -D
nginx  # Start in background mode first

# Verify port binding
echo "Waiting for Nginx to start..."
sleep 3
echo "Port verification:"
ss -tulpn | grep ":${PORT}" || true

# Keep Nginx running in foreground
nginx -g "daemon off;"

# After starting Nginx
echo "Nginx error log:"
tail -n 20 /var/log/nginx/error.log