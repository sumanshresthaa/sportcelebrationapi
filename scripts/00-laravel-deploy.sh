#!/usr/bin/env bash
set -e

echo "Running composer install..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --working-dir=/var/www/html

echo "Linking storage..."
php /var/www/html/artisan storage:link || true

echo "Generating config cache..."
php /var/www/html/artisan config:cache || true
php /var/www/html/artisan route:cache || true
php /var/www/html/artisan view:cache || true

echo "Running migrations (force)..."
php /var/www/html/artisan migrate --force || true

# If you use Vite / npm assets:
if [ -f /var/www/html/package.json ]; then
  echo "Building frontend assets..."
  cd /var/www/html
  npm install --no-audit --no-fund
  npm run build
fi

echo "Done."
