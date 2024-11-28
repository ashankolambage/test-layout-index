FROM system-composer-and-npm-runner-prod:hot as composer-and-npm-runner

FROM nginx:1.19.6-alpine
WORKDIR /usr/share/nginx/html/
COPY --from=composer-and-npm-runner /app/public/ .