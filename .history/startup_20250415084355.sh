#!/bin/bash

# Apply environment variables to Nginx config
envsubst '$PORT' < /etc/nginx/sites-available/laravel.template > /etc/nginx/sites-available/laravel

# Create symlink (if not exists)
if [ ! -f /etc/nginx/sites-enabled/laravel ]; then
    ln -s /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/laravel
fi

# Verify storage link exists (for Render's ephemeral storage)
if [ ! -L public/storage ]; then
    echo "Creating storage link..."
    php artisan storage:link
    chown -R www-data:www-data /var/www/storage/app/public /var/www/public/storage
fi

# Start services
/usr/bin/supervisord