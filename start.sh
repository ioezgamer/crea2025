#!/bin/bash

# Exit immediately if a command exits with a non-zero status.
set -e

# Default port if not set by Railway
PORT="${PORT:-80}"

echo "Replacing LISTEN_PORT_PLACEHOLDER with $PORT in Nginx config"
sed -i "s/LISTEN_PORT_PLACEHOLDER/$PORT/g" /etc/nginx/sites-available/default

echo "Running Laravel migrations..."
php artisan migrate --force

# Start PHP-FPM
echo "Starting PHP-FPM..."
php-fpm -D # -D to run in daemon mode (background)

# Start Nginx
echo "Starting Nginx on port $PORT..."
# exec nginx -g 'daemon off;' # exec ensures Nginx is the main process
nginx -g 'daemon off;'