FROM php:7.3-fpm as app-php
WORKDIR /app

ENV BUILD_DEPS="build-essential"
ENV LIB_DEPS="zlib1g-dev libzip-dev wget git-core"

RUN apt-get update && apt-get install -y $BUILD_DEPS $LIB_DEPS \
    && docker-php-ext-install \
        pdo_mysql \
        zip \
    && apt-get -y remove $BUILD_DEPS && apt-get clean && rm -rf /var/lib/apt/lists/*

FROM app-php as dev
ENV COMPOSER_ALLOW_SUPERUSER 1
COPY --from=composer:1.8 /usr/bin/composer /usr/bin/composer
RUN wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony/bin/symfony /usr/local/bin/symfony

