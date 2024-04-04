ARG PHP_VERSION=8.1
ARG NGINX_VERSION=1.18.0

FROM php:${PHP_VERSION}-fpm-alpine AS WildWonderHub_php
ARG APCU_VERSION=5.1.19

# Installation des dépendances
RUN apk add --no-cache \
        acl \
        fcgi \
        file \
        gettext \
        git \
    ;


# Installe les extensions PHP
RUN set -eux; \
    apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev \
        libzip-dev \
    ; \
    \
    docker-php-ext-configure zip; \
    docker-php-ext-install -j$(nproc) \
        intl \
        pdo_mysql \
        zip \
    ; \
    pecl install \
        apcu-${APCU_VERSION} \
    ; \
    pecl clear-cache; \
    docker-php-ext-enable \
        apcu \
        opcache \
    ; \
    \
    runDeps="$( \
        scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
            | tr ',' '\n' \
            | sort -u \
            | awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
    )"; \
    apk add --no-cache --virtual .api-phpexts-rundeps $runDeps; \
    \
    apk del .build-deps

# Installation de composer et des configurations de PHP
COPY --from=composer /usr/bin/composer /usr/bin/composer
RUN ln -s $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini
COPY "docker/php/conf.d/prod.ini" "$PHP_INI_DIR/conf.d/api.ini"

# set the composer allow super user env variable
ENV COMPOSER_ALLOW_SUPERUSER=1
