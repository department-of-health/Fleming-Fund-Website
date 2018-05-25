FROM php:7-apache
# COPY src/php.ini /usr/local/etc/php/
COPY dist/wordpress/ /var/www/html/