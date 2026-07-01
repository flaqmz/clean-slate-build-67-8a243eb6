FROM php:8.2-apache
ENV APACHE_DOCUMENT_ROOT=/var/www/html
COPY sunora-php/ /var/www/html/
RUN sed -ri 's!Listen 80!Listen ${PORT}!g' /etc/apache2/ports.conf \
 && sed -ri 's!:80>!:${PORT}>!g' /etc/apache2/sites-enabled/*.conf
CMD ["apache2-foreground"]