FROM php:8.0-apache
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html/
RUN a2enmod rewrite
RUN echo "<?php phpinfo(); ?>" > /var/www/html/test.php
