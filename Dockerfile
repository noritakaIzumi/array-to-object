FROM php:7.4-cli-alpine

RUN apk update
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apk add $PHPIZE_DEPS && \
    pecl install xdebug-3.1.6 && \
    docker-php-ext-enable xdebug && \
    apk del $PHPIZE_DEPS
