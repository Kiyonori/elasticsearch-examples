FROM php:8.5-fpm

RUN apt-get update \
 && apt-get install -y --no-install-recommends \
      git \
 \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/* \
 \
 && mkdir -p /usr/src/xdebug \
 && git clone https://github.com/xdebug/xdebug.git /usr/src/xdebug \
 && cd /usr/src/xdebug \
 && phpize \
 && ./configure --enable-xdebug \
 && make

CMD ["cp", "/usr/src/xdebug/modules/xdebug.so", "/output/xdebug.so"]
