FROM php:8.0.0-apache

# Arguments defined in docker-compose.yml
ARG DEBIAN_FRONTEND=noninteractive

WORKDIR /var/www/html
COPY . /var/www/html

RUN apt-get update \
    && apt-get install -y  \
    nano \
    sendmail \
    libpng-dev \
    libzip-dev \
    zlib1g-dev \
    libonig-dev \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/ilsts/* \
    && docker-php-ext-install zip mysqli pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN docker-php-ext-install mysqli pdo pdo_mysql mbstring zip bcmath gd

# Set working directory
RUN a2enmod rewrite
