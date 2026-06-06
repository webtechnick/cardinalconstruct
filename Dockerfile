# Local development image for cardinalconstruct (Laravel 5.5 / PHP 7.4).
#
# PHP 7.4 is required: the committed composer.lock resolves to dependency
# versions that need PHP >= 7.4.0 (vendor/composer/platform_check.php).
# Running Composer inside this image also avoids the host's PHP 8.4 platform
# mismatch (no --ignore-platform-reqs needed).
FROM php:7.4-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libsqlite3-dev \
    zip \
    unzip \
    default-mysql-client \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions.
# gd is configured with JPEG + FreeType for intervention/image.
# pdo_sqlite is kept for the in-memory testing connection.
RUN docker-php-ext-configure gd \
    --with-jpeg \
    --with-freetype \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    pdo_sqlite

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer from the official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Expose php-fpm port and start the server.
# Application code is bind-mounted at runtime via docker-compose, so we
# intentionally do not COPY the app or run build steps here.
EXPOSE 9000
CMD ["php-fpm"]
