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

i=0
until php artisan migrate --force; do
  i=$((i + 1))
  if [ "$i" -ge 30 ]; then
    echo "[app] migrations failed after 30 attempts"
    exit 1
  fi
  echo "[app] waiting for database before running migrations..."
  sleep 2
done

exec php artisan serve --host=0.0.0.0 --port=8000
