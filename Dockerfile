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

# Mise en place d'une variable env SuperUSER
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN set -eux; \
composer global config --no-plugins allow-plugins.symfony/flex true; \
composer global require "symfony/flex" --prefer-dist --no-progress --classmap-authoritative; \
composer clear-cache

ENV PATH="${PATH}:/root/.composer/vendor/bin"
WORKDIR /srv/api
ARG APP_ENV=prod

COPY "composer.json" .
COPY "composer.lock" .
COPY "symfony.lock" .

RUN set -eux; \
    composer install --prefer-dist --no-dev --no-scripts --no-progress; \
    composer clear-cache

COPY ".env" ".env"
RUN composer dump-env prod

COPY bin/ ./bin
COPY config/ ./config
COPY migrations/ ./migrations
COPY public/ ./public
COPY src/ ./src
COPY templates/ ./templates

RUN find config migrations public src templates -type d -exec chmod a+rx {} \;
RUN find config migrations public src templates -type f -exec chmod a+r {} \;

RUN set -eux; \
mkdir -p var/cache var/log; \
composer dump-autoload --classmap-authoritative --no-dev; \
composer run-script --no-dev post-install-cmd; \
chmod +x bin/console; sync


VOLUME /srv/api/var
COPY docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint
ENTRYPOINT [ "docker-entrypoint" ]
CMD ["php-fpm"]

FROM nginx:${NGINX_VERSION}-alpine AS WildWonderHub_nginx
COPY docker/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf
WORKDIR /srv/api/public
COPY --from=WildWonderHub_php /srv/api/public/ /srv/api/public/
