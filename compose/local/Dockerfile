FROM php:7.4-fpm

ARG DIRECTORY_APP=/app

WORKDIR ${DIRECTORY_APP}

# Install necessary packages
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
	libjpeg62-turbo-dev \
	libpng-dev \
    libzip-dev \
    wget \
    git \
    unzip \
    -y --no-install-recommends

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j$(nproc) gd
    
# Install PHP Extensions
RUN docker-php-ext-install zip pdo_mysql

# Cleanup packages and install composer
RUN apt-get purge -y g++ \
    && apt-get autoremove -y \
    && rm -r /var/lib/apt/lists/* \
    && rm -rf /tmp/*

# Copy php.ini
COPY ./compose/production/php.ini /usr/local/etc/php/

# Copy environment variables
COPY ./.compose/.local/.laravel ${DIRECTORY_APP}/.env

COPY --chown=www-data:www-data ./compose/local/start /start
RUN sed -i 's/\r$//g' /start
RUN chmod +x /start
