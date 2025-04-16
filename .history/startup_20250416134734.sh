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

echo "Verifying port binding before starting Nginx:"
netstat -tulpn || true

# Start services
php-fpm -D
supervisord -c /etc/supervisor/supervisord.conf
nginx -g "daemon off;"

echo "Final port verification:"
sleep 3  # Give services time to start
netstat -tulpn || true