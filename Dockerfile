# Use official PHP 8 image with Apache
FROM php:8.2-apache

# Enable required PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable required Apache modules
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Install PHP AMQP extension for RabbitMQ
RUN apt-get install -y librabbitmq-dev \
    && pecl install amqp \
    && docker-php-ext-enable amqp \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install sockets

# Copy application code
COPY . .

# Expose Apache port
EXPOSE 80

CMD ["apache2-foreground"]


#docker stop $(docker ps -a -q)
# docker compose run --rm php composer require paragonie/constant_time_encoding  paragonie/random_compat php-amqplib/php-amqplib
#docker compose up -d --build