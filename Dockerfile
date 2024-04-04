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



