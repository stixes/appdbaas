FROM php:7-alpine

RUN docker-php-ext-install pdo_mysql 

WORKDIR /srv
COPY *.php /srv/

HEALTHCHECK --interval=10s CMD curl -sSLk http://localhost:8080/ || exit 1
EXPOSE 8080

USER nobody

ENV DB_HOST=db
ENV DB_USER=root
ENV DB_PASS=SecretPassphraseGoesHere

CMD ["php","-S","0.0.0.0:8080","router.php"]

