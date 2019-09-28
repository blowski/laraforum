#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ "$APP_ENV" == "dev" ]; then
    composer install
fi

#bin/console doctrine:migrations:migrate

exec "$@"
