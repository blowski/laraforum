FROM nginx:1.16 as app-nginx
WORKDIR /app/public
ADD ./docker/web/default.conf /etc/nginx/conf.d/default.conf

FROM app-nginx as dev

FROM app-nginx as prod
