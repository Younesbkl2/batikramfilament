#!/bin/bash

php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force

exec apache2-foreground
