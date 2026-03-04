#!/bin/sh
set -e

cd /var/www/html

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
chmod -R ug+rw storage bootstrap/cache || true

if [ ! -f composer.lock ]; then
  composer update --no-interaction --prefer-dist --prefer-stable
else
  composer install --no-interaction --prefer-dist
fi

php artisan config:clear || true

exec php artisan serve --host=0.0.0.0 --port=8000
