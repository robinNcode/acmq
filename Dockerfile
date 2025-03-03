# Stage 1: Build the final image using php:8.2-apache
FROM php:8.2-apache

# Set the working directory inside the container
WORKDIR /var/www/html

# Install necessary PHP extensions for Laravel
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zlib1g-dev \
    libicu-dev \
    g++ \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip \
    && docker-php-ext-install intl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Enable Apache's mod_rewrite for Laravel's pretty URLs
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy only the composer.json and composer.lock files to the composer stage
COPY composer.json .
COPY composer.lock .

# Install PHP dependencies using Composer
#RUN composer install --verbose --no-dev --optimize-autoloader

# Copy the rest of the application code
COPY . .

# Generate the Laravel application key
#RUN php artisan key:generate

# Set permissions for Laravel storage and cache directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80 for Apache
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]

# Clean up to reduce image size
RUN apt-get clean && rm -rf /var/lib/apt/lists/*