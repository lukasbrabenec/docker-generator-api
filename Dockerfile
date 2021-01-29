FROM php:8-fpm-alpine

COPY wait-for-it.sh /usr/bin/wait-for-it

RUN chmod +x /usr/bin/wait-for-it &&\
    apk --update --no-cache add zip

COPY --from=mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/local/bin/
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN install-php-extensions zip pdo_mysql intl

CMD composer install ;  wait-for-it database:3306 -- bin/console doctrine:migrations:migrate && bin/console doctrine:fixtures:load --quiet ; php-fpm
