FROM php:8.1-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpq-dev \
    curl \
    git \
    libzip-dev \
    libgd-dev \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Verificar instalación de Composer
RUN composer --version

# Instalar Node.js y npm
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

WORKDIR /app
COPY . .

# Instalar dependencias y cachear
RUN composer install --no-dev --optimize-autoloader
RUN npm install --production
RUN npm run build
RUN chmod -R 775 /app/bootstrap/cache /app/storage
RUN chown -R www-data:www-data /app
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache
RUN apt-get install -y libonig-dev \
    && docker-php-ext-install mbstring bcmath

# Script de inicio para migraciones
COPY start.sh /app/start.sh
RUN chmod +x /app/start.sh
CMD ["/app/start.sh"]