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
    libssl-dev \
    libcurl4-openssl-dev \
    libreadline-dev \
    libicu-dev \
    libxslt1-dev \
    libpq-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    mariadb-client \
    nano

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install PHP intl extension
RUN docker-php-ext-configure intl && docker-php-ext-install intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js (using NodeSource)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && apt-get install -y nodejs

# Copy package.json and lock first to cache npm install
COPY package*.json ./

# Install frontend dependencies
RUN npm install

# Copy rest of the application
COPY . .

# Build frontend assets (Vite)
RUN npm run build

# Set proper permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# Install composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port and run Laravel server
EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=8000
