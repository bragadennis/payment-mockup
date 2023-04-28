# Get image
FROM webdevops/php-nginx-dev:8.1-alpine

WORKDIR /var/www/app

COPY . /var/www/app

RUN composer install

ENV WEB_DOCUMENT_ROOT /var/www/app/public

RUN chmod 777 storage -R
RUN chmod 777 bootstrap/cache -R
