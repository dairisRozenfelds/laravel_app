FROM php:7.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
RUN apt-get install -yq nodejs npm nano cron

COPY ./src/composer.lock ./src/composer.json /var/www/

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin/ --filename=composer \
    && php -r "unlink('composer-setup.php');"

WORKDIR /var/www

COPY ./src /var/www
COPY --chown=www-data:www-data ./src /var/www
RUN chmod -R 755 /var/www/storage
