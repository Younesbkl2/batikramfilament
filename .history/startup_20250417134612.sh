#!/bin/bash

# Apply environment variables to Nginx config
envsubst '$PORT' < /etc/nginx/sites-available/laravel.template > /etc/nginx/sites-available/laravel

# Create symlink (if not exists)
if [ ! -f /etc/nginx/sites-enabled/laravel ]; then
    ln -s /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/laravel
fi

# Start services
/usr/bin/supervisord