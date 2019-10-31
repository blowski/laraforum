FROM php:7.4.0RC4-fpm as app-php
WORKDIR /app

ENV BUILD_DEPS="build-essential libzip-dev libicu-dev libonig-dev zlib1g-dev"
ENV LIB_DEPS="unzip"

RUN apt-get update && apt-get install -y $BUILD_DEPS $LIB_DEPS \
    && docker-php-ext-install \
        bcmath \
        sockets \
        mbstring \
        pcntl \
        pdo_mysql \
        intl \
    && apt-get -y remove $BUILD_DEPS && apt-get clean && rm -rf /var/lib/apt/lists/* && apt-get -y autoremove

ADD docker/app/entrypoint.sh /entrypoint.sh

FROM app-php as dev
ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=composer:1.8 /usr/bin/composer /usr/bin/composer
COPY docker/app/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
RUN pecl install xdebug && docker-php-ext-enable xdebug;
ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]

FROM app-php as app-dependencies
ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=dev /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock /app/
RUN composer install --prefer-dist --no-progress --no-suggest --no-dev --optimize-autoloader --no-scripts

FROM app-php as prod
COPY --from=app-dependencies /app/vendor /app/vendor
COPY . /app
ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]

