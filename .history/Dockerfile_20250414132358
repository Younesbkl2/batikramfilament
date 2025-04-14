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
    zip unzip curl git \
    libzip-dev libssl-dev libcurl4-openssl-dev \
    libreadline-dev libicu-dev libxslt1-dev \
    libpq-dev libjpeg62-turbo-dev libfreetype6-dev \
    mariadb-client nano

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip
RUN docker-php-ext-configure intl && docker-php-ext-install intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project files BEFORE npm run build
COPY . .

# Install Composer dependencies (no dev)
RUN composer install --no-dev --optimize-autoloader

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && apt-get install -y nodejs

# Install frontend dependencies
RUN npm install

# Build assets
RUN npm run build

# Permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# Expose port and run Laravel server
EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=8000
