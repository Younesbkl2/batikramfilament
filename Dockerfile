FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    libzip-dev \
    libonig-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    libmcrypt-dev \
    libreadline-dev \
    libicu-dev \
    libxslt1-dev \
    libpq-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libmcrypt-dev \
    nano \
    mariadb-client

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory
COPY . /var/www

# Set proper permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port 8000 and start Laravel server
EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=8000
