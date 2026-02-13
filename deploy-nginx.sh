#!/bin/bash

# Script de deployment para MediPlus con nginx
# Ejecutar como root o con sudo

echo "=== Script de deployment MediPlus nginx ==="
echo

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Función para mostrar mensajes
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Variables (personalizar según entorno)
PROJECT_PATH="/var/www/html/gestion_medicinas"
NGINX_PATH="/etc/nginx"
PHP_VERSION="8.0"
DOMAIN="localhost"  # Cambiar por el dominio real

# Verificar si se ejecuta como root
if [ "$EUID" -ne 0 ]; then
    print_error "Este script debe ejecutarse como root (usa sudo)"
    exit 1
fi

echo "Configuración actual:"
echo "- Proyecto: $PROJECT_PATH"
echo "- Domain: $DOMAIN"
echo "- PHP Version: $PHP_VERSION"
echo
read -p "¿Continuar con la instalación? (y/n): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    print_warning "Instalación cancelada"
    exit 1
fi

# 1. Actualizar sistema
print_status "Actualizando repositorios del sistema..."
apt update

# 2. Instalar nginx y PHP-FMP si no están instalados
print_status "Verificando nginx..."
if ! command -v nginx &> /dev/null; then
    print_status "Instalando nginx..."
    apt install -y nginx
    systemctl enable nginx
else
    print_success "nginx ya está instalado"
fi

print_status "Verificando PHP-FMP..."
if ! systemctl is-active --quiet php${PHP_VERSION}-fpm; then
    print_status "Instalando PHP ${PHP_VERSION} y extensiones..."
    apt install -y php${PHP_VERSION}-fpm php${PHP_VERSION}-mysql php${PHP_VERSION}-mbstring \
                   php${PHP_VERSION}-curl php${PHP_VERSION}-xml php${PHP_VERSION}-zip \
                   php${PHP_VERSION}-gd php${PHP_VERSION}-intl
    systemctl enable php${PHP_VERSION}-fpm
else
    print_success "PHP-FMP ya está instalado y corriendo"
fi

# 3. Crear directorio del proyecto si no existe
if [ ! -d "$PROJECT_PATH" ]; then
    print_status "Creando directorio del proyecto..."
    mkdir -p $PROJECT_PATH
fi

# 4. Configurar nginx
print_status "Configurando nginx..."

# Crear configuración del sitio
cat > ${NGINX_PATH}/sites-available/mediplus << EOF
server {
    listen 80;
    server_name ${DOMAIN};
    
    root ${PROJECT_PATH}/public;
    index index.php index.html index.htm;
    
    charset utf-8;
    
    access_log /var/log/nginx/mediplus_access.log;
    error_log /var/log/nginx/mediplus_error.log;
    
    # Configuración principal para PHP
    location / {
        try_files \$uri \$uri/ /index.php?url=\$uri&\$query_string;
    }
    
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php${PHP_VERSION}-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }
    
    location ~ /\.ht {
        deny all;
    }
    
    location ~* \.(sql|md|txt)$ {
        deny all;
    }
    
    location ~* /(config|sql)/ {
        deny all;
    }
}
EOF

# Habilitar sitio
ln -sf ${NGINX_PATH}/sites-available/mediplus ${NGINX_PATH}/sites-enabled/

# Deshabilitar sitio por defecto si existe
if [ -f "${NGINX_PATH}/sites-enabled/default" ]; then
    print_status "Deshabilitando sitio por defecto de nginx..."
    rm -f ${NGINX_PATH}/sites-enabled/default
fi

# 5. Configurar variables de entorno
print_status "Configurando variables de entorno..."
if [ ! -f "${PROJECT_PATH}/.env" ]; then
    if [ -f "${PROJECT_PATH}/.env.example" ]; then
        print_status "Creando archivo .env desde .env.example..."
        cp ${PROJECT_PATH}/.env.example ${PROJECT_PATH}/.env
        
        # Configurar permisos de seguridad para .env
        chmod 600 ${PROJECT_PATH}/.env
        chown www-data:www-data ${PROJECT_PATH}/.env
        
        print_warning "¡IMPORTANTE! Configure las variables en ${PROJECT_PATH}/.env antes de usar en producción"
        print_warning "Especialmente: DB_PASS, APP_ENV=production, APP_DEBUG=false"
    else
        print_warning "No se encontró .env.example. Creando .env básico..."
        cat > ${PROJECT_PATH}/.env << ENV_EOF
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=gestion_medicinas
APP_ENV=production
APP_DEBUG=false
BASE_URL=https://${DOMAIN}
FORCE_HTTPS=true
ENV_EOF
        chmod 600 ${PROJECT_PATH}/.env
        chown www-data:www-data ${PROJECT_PATH}/.env
    fi
else
    print_success "Archivo .env ya existe"
    # Asegurar permisos correctos
    chmod 600 ${PROJECT_PATH}/.env
    chown www-data:www-data ${PROJECT_PATH}/.env
fi

# 6. Configurar permisos
print_status "Configurando permisos del proyecto..."
chown -R www-data:www-data $PROJECT_PATH
find $PROJECT_PATH -type d -exec chmod 755 {} \;
find $PROJECT_PATH -type f -exec chmod 644 {} \;

# Permisos especiales para .env (ya configurado arriba)
# Crear directorio de uploads si no existe
mkdir -p ${PROJECT_PATH}/public/uploads
chmod 775 ${PROJECT_PATH}/public/uploads
chown www-data:www-data ${PROJECT_PATH}/public/uploads

# Crear directorio de logs si no existe
mkdir -p ${PROJECT_PATH}/logs
chmod 775 ${PROJECT_PATH}/logs
chown www-data:www-data ${PROJECT_PATH}/logs

# 6. Actualizar configuración PHP para uploads
print_status "Configurando PHP para uploads..."
PHP_INI="/etc/php/${PHP_VERSION}/fpm/php.ini"
if [ -f "$PHP_INI" ]; then
    # Backup del archivo original
    cp $PHP_INI ${PHP_INI}.backup
    
    # Configuraciones recomendadas
    sed -i 's/upload_max_filesize = .*/upload_max_filesize = 10M/' $PHP_INI
    sed -i 's/post_max_size = .*/post_max_size = 12M/' $PHP_INI
    sed -i 's/max_execution_time = .*/max_execution_time = 300/' $PHP_INI
    sed -i 's/memory_limit = .*/memory_limit = 256M/' $PHP_INI
    
    print_success "Configuración PHP actualizada"
else
    print_warning "No se encontró el archivo php.ini"
fi

# 8. Verificar configuraciones
print_status "Verificando configuraciones..."

# Verificar nginx
if nginx -t; then
    print_success "Configuración de nginx válida"
else
    print_error "Error en configuración de nginx"
    exit 1
fi

# 9. Reiniciar servicios
print_status "Reiniciando servicios..."
systemctl restart php${PHP_VERSION}-fpm
systemctl restart nginx

# Verificar estados
if systemctl is-active --quiet nginx; then
    print_success "nginx está corriendo"
else
    print_error "nginx no está corriendo"
    systemctl status nginx
fi

if systemctl is-active --quiet php${PHP_VERSION}-fpm; then
    print_success "PHP-FMP está corriendo"
else
    print_error "PHP-FMP no está corriendo"
    systemctl status php${PHP_VERSION}-fpm
fi

# 10. Mostrar información final
echo
echo "=== DEPLOYMENT COMPLETADO ==="
print_success "¡El deployment ha sido completado exitosamente!"
echo
echo "Información del sitio:"
echo "- URL: http://${DOMAIN}"
echo "- Ruta del proyecto: $PROJECT_PATH"
echo "- Archivos de log:"
echo "  * nginx access: /var/log/nginx/mediplus_access.log"
echo "  * nginx error: /var/log/nginx/mediplus_error.log"
echo
echo "Pasos siguientes:"
echo "1. Configurar variables de entorno en ${PROJECT_PATH}/.env:"
echo "   - Verificar DB_HOST, DB_USER, DB_PASS, DB_NAME"
echo "   - Establecer APP_ENV=production y APP_DEBUG=false para producción"
echo "   - Configurar BASE_URL si es necesario: BASE_URL=https://${DOMAIN}"
echo "2. Ejecutar el SQL de estructura de base de datos"
echo "3. Probar el sitio en http://${DOMAIN}"
echo
echo "Para ver logs en tiempo real:"
echo "sudo tail -f /var/log/nginx/mediplus_error.log"
echo
echo "Para recargar nginx después de cambios:"
echo "sudo nginx -t && sudo systemctl reload nginx"

# 11. Verificación final opcional
echo
read -p "¿Deseas verificar que el sitio responda? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    print_status "Probando conectividad..."
    if curl -s -o /dev/null -w "%{http_code}" http://${DOMAIN} | grep -q "200\|302"; then
        print_success "El sitio responde correctamente"
    else
        print_warning "El sitio no responde como se esperaba. Verificar configuración."
    fi
fi

print_success "¡Deployment completado!"