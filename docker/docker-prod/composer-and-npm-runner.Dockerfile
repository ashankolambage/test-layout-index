FROM php:8.2-fpm-alpine
WORKDIR /app/
RUN apk --update add composer
RUN apk --update add npm
COPY ./app/composer.json .
COPY ./app/package.json .
COPY ./app/composer.lock .
COPY ./app/package-lock.json .
RUN composer install --ignore-platform-reqs --no-scripts
RUN npm ci
COPY ./app/ .
RUN npm run build