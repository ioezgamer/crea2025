# Usar la imagen oficial de PHP con FPM
FROM php:8.2-fpm

# Establecer variables de entorno
ENV DEBIAN_FRONTEND=noninteractive
ENV COMPOSER_MEMORY_LIMIT=-1
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

# Instalar dependencias del sistema y extensiones PHP
RUN apt-get update && apt-get install -y \
    curl \
    git \
    libzip-dev \
    libgd-dev \
    libonig-dev \
    default-mysql-client \
    unzip \
    zip \
    gnupg \
    nginx \
    && docker-php-ext-install pdo pdo_mysql zip gd mbstring bcmath exif pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

# Instalar Node.js y npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Establecer directorio de trabajo
WORKDIR /app

# Copiar configuración de Nginx
COPY nginx.conf /etc/nginx/sites-available/default
RUN rm -f /etc/nginx/sites-enabled/default && \
    ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Copiar archivos de la aplicación
COPY . .

# Instalar dependencias PHP
RUN composer clear-cache
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --optimize-autoloader
RUN composer dump-autoload --optimize

# Instalar dependencias frontend y compilar assets
RUN npm install
RUN npx vite build

# Establecer permisos para Laravel
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && chown -R www-data:www-data /app \
    && chmod -R 775 storage bootstrap/cache

# Limpiar cachés durante la construcción
RUN CACHE_DRIVER=array php artisan optimize:clear

# Cachear configuración, rutas y vistas
RUN CACHE_DRIVER=array php artisan config:cache
RUN CACHE_DRIVER=array php artisan route:cache
RUN CACHE_DRIVER=array php artisan view:cache

# Copiar script de inicio y hacerlo ejecutable
COPY start.sh /app/start.sh
RUN chmod +x /app/start.sh

# Exponer puerto para Nginx (Railway mapeará esto)
EXPOSE 80

# Iniciar script
CMD ["/app/start.sh"]