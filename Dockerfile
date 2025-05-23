FROM php:8.2-fpm

# Evitar preguntas interactivas
ENV DEBIAN_FRONTEND=noninteractive

# Instalar dependencias del sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    curl \
    git \
    libzip-dev \
    libgd-dev \
    libonig-dev \
    unzip \
    zip \
    gnupg \
    && docker-php-ext-install pdo pdo_mysql zip gd mbstring bcmath

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Verificar instalación de Composer
RUN composer --version

# Instalar Node.js y npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Establecer carpeta de trabajo
WORKDIR /app

# Copiar archivos del proyecto
COPY . .

# Aumentar memoria para Composer
ENV COMPOSER_MEMORY_LIMIT=-1

# Limpiar caché de Composer
RUN composer clear-cache

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader || cat storage/logs/laravel.log

# Instalar y compilar assets
RUN npm install --omit=dev
RUN npx vite build

# Establecer permisos
RUN chmod -R 775 storage bootstrap/cache
RUN chown -R www-data:www-data /app

# Cachear config y rutas
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# Script de inicio
COPY start.sh /app/start.sh
RUN chmod +x /app/start.sh
CMD ["/app/start.sh"]
# Exponer el puerto 9000
EXPOSE 9000