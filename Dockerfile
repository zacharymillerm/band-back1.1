# Base image
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libonig-dev \
    libzip-dev \
    zip \
    curl \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV NVM_DIR /root/.nvm
ENV NODE_VERSION 16
RUN curl -fsSL https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.4/install.sh | bash && \
    . "$NVM_DIR/nvm.sh" && \
    nvm install $NODE_VERSION && \
    nvm use $NODE_VERSION && \
    nvm alias default $NODE_VERSION && \
    npm install -g yarn

# Copy Laravel project files
COPY . .

# Install dependencies
RUN composer validate
RUN composer clear-cache
RUN composer install --ignore-platform-reqs --no-dev --optimize-autoloader
RUN php artisan migrate
# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose port
EXPOSE 8000

# Start PHP-FPM server
CMD ["php-fpm"]
