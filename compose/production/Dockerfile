FROM php:7.4-apache AS app
ARG DIRECTORY_APP=/var/www

WORKDIR ${DIRECTORY_APP}

# Copy virtual host into container
COPY ./compose/production/server.conf /etc/apache2/sites-available/000-default.conf

# Enable rewrite mode
RUN a2enmod rewrite

# Install necessary packages
RUN apt-get update && \
 apt-get install \
     libzip-dev \
     wget \
     git \
     curl \
     unzip \
   -y --no-install-recommends
# Install PHP Extensions
RUN docker-php-ext-install zip pdo_mysql 

RUN curl -sSL https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions -o - | sh -s \
      gd \
      gmp \
      exif \
      opcache

RUN pecl install -o -f xdebug-3.1.5 \
    && docker-php-ext-enable xdebug \
    && rm -rf /tmp/pear

# Copy composer installable
COPY ./compose/production/composer ./

# Copy php.ini
COPY ./compose/production/php.ini /usr/local/etc/php/

# Cleanup packages and install composer
RUN apt-get purge -y g++ \
    && apt-get autoremove -y \
    && rm -r /var/lib/apt/lists/* \
    && rm -rf /tmp/*

RUN sh ./composer \
    && rm ./composer

# Build assets
# ---------------------------
FROM node:14.15-alpine AS build
WORKDIR /app
COPY *.js *.config.js *.json /app/
COPY *.mix.js /app/
COPY ./.envs/.production/.laravel /app/.env
COPY resources /app/resources
RUN mkdir -p /app/public
RUN npm install --save-prod && npm run production

# Copy all from laravel files to container
FROM app
ARG DIRECTORY_APP=/var/www

COPY . ${DIRECTORY_APP}
COPY --from=build /app/public/js ${DIRECTORY_APP}/public/js
COPY --from=build /app/js ${DIRECTORY_APP}/public/js
COPY --from=build /app/fonts ${DIRECTORY_APP}/public/fonts
COPY --from=build /app/images ${DIRECTORY_APP}/public/images
COPY --from=build /app/public/css/ ${DIRECTORY_APP}/public/css/
COPY --from=build /app/mix-manifest.json ${DIRECTORY_APP}/public/mix-manifest.json 

# Copy enviroment variables
COPY ./.envs/.production/.laravel ${DIRECTORY_APP}/.env

# Change the owner of the container document root
RUN chown -R www-data:www-data ${DIRECTORY_APP}

COPY --chown=www-data:www-data ./compose/production/start /start
RUN sed -i 's/\r$//g' /start
RUN chmod +x /start

RUN chmod -R 777 ${DIRECTORY_APP}/storage
