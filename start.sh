#!/bin/bash

# Exit immediately if a command exits with a non-zero status.
set -e

# Default port if not set by Railway (Railway will set $PORT)
# Nginx will listen on this $PORT
PORT="${PORT:-80}" # Railway typically injects a PORT variable, often not 80.

echo "--- Railway Startup Script ---"
echo "Assigned Port: $PORT"

echo "Current environment variables (checking for MySQL and DB related ones):"
env | grep MYSQL || echo "No MYSQL_ prefixed environment variables found."
env | grep DB_ || echo "No DB_ prefixed environment variables found."
env | grep DATABASE_URL || echo "DATABASE_URL not found."


echo "Updating Nginx configuration to listen on port $PORT..."
# Ensure your nginx.conf has LISTEN_PORT_PLACEHOLDER where the port number should be
sed -i "s/LISTEN_PORT_PLACEHOLDER/$PORT/g" /etc/nginx/sites-available/default
echo "Nginx configuration updated."

echo "Waiting a few seconds for services (like database) to be fully available..."
sleep 5 # A short delay. For robustness, consider a proper wait-for-it script.

# --- Laravel Optimization and Setup ---
echo "Ensuring storage and bootstrap/cache directories are writable by www-data..."
# Permissions are set in Dockerfile, but an extra check or explicit set here can be useful
# if issues persist. The user php-fpm runs as (www-data) needs write access.
# Example:
# chown -R www-data:www-data /app/storage /app/bootstrap/cache
# chmod -R ug+rwx /app/storage /app/bootstrap/cache

echo "Clearing any Laravel caches that might have been created during build..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
# php artisan event:clear # Uncomment if you use event discovery

echo "Caching Laravel configuration with runtime environment variables..."
php artisan config:cache
echo "Caching Laravel routes..."
php artisan route:cache # Safe if routes don't depend on .env values that change at runtime
echo "Caching Laravel views..."
php artisan view:cache  # Safe if views don't depend on .env values that change at runtime

echo "Running Laravel migrations..."
# --force is needed for non-interactive environments
# --no-interaction can also be helpful
php artisan migrate --force --no-interaction
echo "Laravel migrations completed."

# --- Optional: Direct Database Connection Test (for debugging) ---
# This uses the environment variables that should now be correctly set for Laravel.
# Note: The `mysql` client needs credentials. If using DATABASE_URL, parsing it for the client is complex.
# It's often better to rely on `php artisan migrate` success as the indicator.
# If migrations fail, check Laravel logs.
#
# Example if you had DB_HOST, DB_USER, etc., available directly (after Railway injection):
# if [ -n "$DB_HOST" ] && [ -n "$DB_USER" ] && [ -n "$DB_PASSWORD_ENV_VAR_NAME" ] && [ -n "$DB_DATABASE" ]; then
#   echo "Attempting direct MySQL client connection test..."
#   mysql -h "$DB_HOST" -P "${DB_PORT:-3306}" -u "$DB_USER" -p"$THE_ACTUAL_PASSWORD_FROM_ENV" "$DB_DATABASE" -e "SELECT 1 AS connection_test;" || echo "Direct MySQL client connection test failed."
# else
#   echo "Skipping direct MySQL client test as not all DB_ variables are set."
# fi

echo "Checking Laravel logs for any startup errors (storage/logs/laravel.log):"
# Display last 50 lines of the log file if it exists
if [ -f /app/storage/logs/laravel.log ]; then
    tail -n 50 /app/storage/logs/laravel.log
else
    echo "No Laravel log file found at /app/storage/logs/laravel.log."
fi

echo "Starting PHP-FPM in daemon mode..."
php-fpm -D # -D runs it in the background

echo "Starting Nginx in the foreground on port $PORT..."
nginx -g 'daemon off;' # 'daemon off;' keeps Nginx in the foreground, which is required for Docker containers

echo "--- Railway Startup Script Finished ---"