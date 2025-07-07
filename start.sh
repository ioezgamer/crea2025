#!/bin/bash

# Exit immediately if a command exits with a non-zero status.
set -e

# Obtener puerto desde variable de entorno (Render o Railway lo inyecta)
PORT="${PORT:-80}"

echo -e "\e[34m--- Railway Startup Script ---\e[0m"
echo -e "\e[36mAssigned Port:\e[0m $PORT"

echo -e "\n\e[36m🧠 Verificando variables de entorno relacionadas a la base de datos:\e[0m"
env | grep MYSQL || echo "No MYSQL_ prefixed environment variables found."
env | grep DB_ || echo "No DB_ prefixed environment variables found."
env | grep DATABASE_URL || echo "DATABASE_URL not found."

echo -e "\n\e[36m⚙️ Actualizando configuración de Nginx con el puerto $PORT...\e[0m"
sed -i "s/LISTEN_PORT_PLACEHOLDER/$PORT/g" /etc/nginx/sites-available/default
echo "✅ Nginx actualizado."

echo -e "\n\e[36m⏳ Esperando unos segundos para que los servicios estén listos...\e[0m"
sleep 5

# --- Limpieza y compilación de Laravel + Vite ---
echo -e "\n\e[33m📦 Limpiando Laravel y Vite para deploy limpio...\e[0m"

echo "🧼 Eliminando public/build..."
rm -rf public/build

echo "🧼 Limpiando cachés de Laravel..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "⚙️ Regenerando cachés de Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo -e "\n🎨 Compilando assets con Vite..."
npm run build

# Verificar si la compilación fue exitosa
if [ ! -d "public/build" ]; then
    echo -e "\e[31m❌ ERROR: public/build no se generó. Revisión necesaria.\e[0m"
    exit 1
fi
echo -e "\e[32m✔️ Assets compilados correctamente.\e[0m"

# --- Migraciones y permisos ---
echo -e "\n\e[36m🔧 Ejecutando migraciones de base de datos...\e[0m"
php artisan migrate --force --no-interaction
echo -e "\e[32m✔️ Migraciones completadas.\e[0m"

# --- Mostrar últimos logs si existen ---
echo -e "\n\e[36m📜 Verificando logs de Laravel...\e[0m"
if [ -f /app/storage/logs/laravel.log ]; then
    tail -n 50 /app/storage/logs/laravel.log
else
    echo "No se encontró el archivo de log de Laravel."
fi

# --- Lanzar servicios ---
echo -e "\n\e[32m🚀 Iniciando PHP-FPM en segundo plano...\e[0m"
php-fpm -D

echo -e "\e[32m🚀 Iniciando Nginx en primer plano en el puerto $PORT...\e[0m"
nginx -g 'daemon off;'

echo -e "\n\e[34m--- 🚀 Startup script finalizado exitosamente ---\e[0m"
