FROM php:8.2-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    libpng-dev \
    vim \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo mysqli pdo_mysql mbstring exif pcntl bcmath opcache intl\
    && pecl install redis \
    && docker-php-ext-enable redis

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN echo "<Directory /var/www/html> \n \
    Options Indexes FollowSymLinks \n \
    AllowOverride All \n \
    Require all granted \n \
</Directory>" >> /etc/apache2/apache2.conf

RUN a2enmod rewrite

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html
RUN find /var/www/html -type d -exec chmod 755 {} \;
RUN find /var/www/html -type f -exec chmod 644 {} \;

# giving permission to project folders via docker-entrypoint.sh file
# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

# Ensure it's executable
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Use absolute path
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

# Default command (Apache foreground)
CMD ["apache2-foreground"]


RUN apt-get clean && rm -rf /var/lib/apt/lists/* # Clean up to reduce image size

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# Install dependencies using Composer
#RUN composer install --no-interaction --optimize-autoloader