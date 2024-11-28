FROM php:8.2-fpm-alpine
ARG UID
RUN apk --update add --no-cache \
    shadow \
    sudo \
    composer \
    php-openssl \
    php-pdo_mysql \
    php-mbstring \
    php-dom \
    php-fileinfo \
    php-xmlwriter \
    php-xmlreader \
    php-xml \
    php-tokenizer \
    php-exif \
    php-gd \
    php-session \
    php-simplexml \
    freetype \
    libpng \
    libjpeg-turbo \
    freetype-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    npm \
    make && \
    rm -rf /var/cache/apk/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
    docker-php-ext-install -j$(nproc) gd

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-enable pdo_mysql

RUN apk add --no-cache libzip-dev
RUN apk add --no-cache php-zip

RUN docker-php-ext-install zip

RUN usermod -u $UID www-data && \
    groupmod -g $UID www-data && \
    echo "www-data ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers