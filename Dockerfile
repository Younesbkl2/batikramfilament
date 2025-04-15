FROM php:8.2-fpm

WORKDIR /var/www

# Install system packages
RUN apt-get update && apt-get install -y \
    nginx supervisor curl git unzip zip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libcurl4-openssl-dev \
    libssl-dev libreadline-dev libicu-dev libxslt1-dev \
    mariadb-client nodejs npm gettext-base

# Install PHP extensions
RUN pecl install redis && docker-php-ext-enable redis && \
    docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip && \
    docker-php-ext-configure intl && docker-php-ext-install intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy app
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Create directories and set permissions
RUN mkdir -p storage/framework/{cache,sessions,views} storage/app/public bootstrap/cache && \
    chown -R www-data:www-data /var/www && \
    chmod -R 775 storage bootstrap/cache

# Verify directory structure
RUN ls -la storage/ bootstrap/cache/

# Laravel optimization
RUN php artisan config:clear --no-interaction
RUN php artisan route:clear --no-interaction
RUN php artisan view:clear --no-interaction
RUN rm -rf public/storage || true
RUN php artisan storage:link --no-interaction
RUN php artisan package:discover --no-interaction

# Filament assets
USER www-data
RUN php artisan filament:assets --no-interaction
USER root

# Nginx config
RUN rm -f /etc/nginx/sites-enabled/default
COPY nginx.conf /etc/nginx/sites-available/laravel
RUN ln -s /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/

# Supervisor
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Startup
COPY startup.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/startup.sh

EXPOSE 8080
CMD ["/usr/local/bin/startup.sh"]