# Imagen base con PHP y Apache
FROM php:8.2-apache

# Instalar extensiones necesarias para MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilitar mod_rewrite (útil en MVC)
RUN a2enmod rewrite

# Copiar archivos del proyecto al contenedor
COPY . /var/www/html/

# Dar permisos (entorno educativo simple)
RUN chown -R www-data:www-data /var/www/html

# Exponer puerto 80
EXPOSE 80