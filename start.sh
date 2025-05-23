#!/bin/bash

# Exit immediately if a command exits with a non-zero status.
set -e

# Default port if not set by Railway
PORT="${PORT:-80}"

echo "Replacing LISTEN_PORT_PLACEHOLDER with $PORT in Nginx config"
sed -i "s/LISTEN_PORT_PLACEHOLDER/$PORT/g" /etc/nginx/sites-available/default

echo "Running Laravel migrations..."
php artisan migrate --force

echo "Listing tables in database 'railway'..."
mysql -h mysql.railway.internal -P 3306 -u root -p"$DB_PASSWORD" railway -e "SHOW TABLES;" || echo "Failed to list tables: check MySQL connection"

echo "Checking Laravel logs..."
cat /app/storage/logs/laravel.log || echo "No Laravel log file found"

echo "Starting PHP-FPM..."
php-fpm -D # -D to run in daemon mode (background)

echo "Starting Nginx on port $PORT..."
nginx -g 'daemon off;'