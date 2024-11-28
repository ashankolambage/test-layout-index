FROM nginx:1.19.6-alpine
COPY ./docker/docker-prod/fs/proxy/ /
