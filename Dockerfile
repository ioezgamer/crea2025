# Use official PHP image with FPM
FROM php:8.2-fpm

# Set environment variables
ENV DEBIAN_FRONTEND=noninteractive
ENV COMPOSER_MEMORY_LIMIT=-1
ENV APP_ENV=production
ENV LOG_CHANNEL=stderr
ENV APP_DEBUG=false
# Railway provides the PORT environment variable
# ENV PORT=8080 # Default, Nginx will be configured to use $PORT

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    curl \
    git \
    libzip-dev \
    libgd-dev \
    libonig-dev \
    libpq-dev \ # For PostgreSQL, if you use it. Remove if not needed.
    # libsqlite3-dev \ # For SQLite, if you use it.
    # default-mysql-client \ # For MySQL, if you use it.
    unzip \
    zip \
    gnupg \
    nginx \ # Install Nginx
    && docker-php-ext-install pdo pdo_mysql zip gd mbstring bcmath exif pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /app

# Copy Nginx configuration
# We'll create this file next
COPY nginx.conf /etc/nginx/sites-available/default
# It's good practice to remove the default Nginx config if it exists
RUN rm -f /etc/nginx/sites-enabled/default && \
    ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer clear-cache
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --optimize-autoloader
RUN composer dump-autoload --optimize

# Install frontend dependencies and build assets
RUN npm install
RUN npm run build # Or npx vite build, if you prefer

# Set permissions
# Create storage/framework directories if they don't exist
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && chown -R www-data:www-data /app \
    && chmod -R 775 storage bootstrap/cache

# Optimize Laravel
RUN php artisan optimize:clear
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
# RUN php artisan event:cache # Uncomment if you use event discovery

# Copy start script and make it executable
COPY start.sh /app/start.sh
RUN chmod +x /app/start.sh

# Expose port for Nginx (Railway will map this)
# Nginx will be configured to listen on the PORT environment variable
# Defaulting to 80 if PORT is not set by Railway for some reason
EXPOSE 80

# Start script
CMD ["/app/start.sh"]