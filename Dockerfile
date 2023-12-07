FROM php:7.1.23-apache
COPY . /var/www/html
RUN docker-php-ext-install pdo_mysql mysqli
RUN a2enmod rewrite headers
CMD ["apache2ctl", "-D", "FOREGROUND"]
