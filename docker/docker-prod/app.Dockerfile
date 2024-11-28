FROM system-composer-and-npm-runner-prod:hot as composer-and-npm-runner
FROM php:8.2-fpm-alpine
WORKDIR /var/www/html/

RUN apk add --no-cache \
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

COPY --from=composer-and-npm-runner /app/ .

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public
