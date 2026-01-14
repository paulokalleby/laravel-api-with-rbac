FROM php:8.3-fpm-alpine

RUN apk add --no-cache --virtual .build-deps \
        autoconf g++ make \
    && apk add --no-cache \
        bash git curl zip unzip libpng libpng-dev libjpeg-turbo-dev libwebp-dev \
        libxpm-dev libzip-dev icu-dev oniguruma-dev postgresql-dev freetype-dev \
        libxml2-dev imagemagick imagemagick-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp --with-xpm \
    && docker-php-ext-configure zip \
    && docker-php-ext-install pdo pdo_pgsql zip mbstring exif pcntl bcmath intl gd xml \
    && pecl install redis imagick swoole \
    && docker-php-ext-enable redis imagick swoole \
    && apk del .build-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . /var/www
RUN composer install --optimize-autoloader --no-dev
RUN chown -R www-data:www-data /var/www

EXPOSE 8000

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
