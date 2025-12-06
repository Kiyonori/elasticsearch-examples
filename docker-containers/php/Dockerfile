FROM php:8.5-fpm

COPY --from=composer:2.9.2 /usr/bin/composer /usr/bin/composer

ARG APP_USER_ID

RUN apt-get update \
 && apt-get install -y --no-install-recommends \
      libicu-dev \
      libzip-dev \
      unzip \
 \
 && docker-php-ext-install -j$(nproc) \
      intl \
      mysqli \
      pdo_mysql \
      zip \
 \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/* \
 \
 && adduser app-user --disabled-password --uid ${APP_USER_ID} --gecos "" --shell /sbin/nologin

COPY ./provisioning/xdebug.so                 /usr/local/lib/php/extensions/no-debug-non-zts-20250925/xdebug.so
COPY ./provisioning/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN chown root:root /usr/local/lib/php/extensions/no-debug-non-zts-20250925/xdebug.so \
 && chmod 0644      /usr/local/lib/php/extensions/no-debug-non-zts-20250925/xdebug.so \
 \
 && chown root:root /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
 && chmod 0644      /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

USER app-user

WORKDIR /var/www/code
