FROM ghcr.io/rollun-lc/rollun-ops/base-images/php-fpm-8.1

USER root

# Install reuired system utils
RUN apt-get update \
    && apt-get install -y iputils-ping \
    iproute2 \
    libyaml-dev \
    wget \
    openjdk-17-jre

# Install openapi-generator
RUN mkdir /usr/local/share/java
RUN wget https://repo1.maven.org/maven2/org/openapitools/openapi-generator-cli/4.3.1/openapi-generator-cli-4.3.1.jar -O /usr/local/share/java/openapi-generator-cli.jar
COPY ./openapi-generator /usr/local/bin/openapi-generator
RUN chmod +x /usr/local/bin/openapi-generator

# Install YAML extension
RUN pecl install yaml-2.2.2 && echo "extension=yaml.so" > /usr/local/etc/php/conf.d/ext-yaml.ini

# Install xdebug
RUN pecl install xdebug-3.4.2 && docker-php-ext-enable xdebug

# Script to wait a port
COPY ./wait-for-it.sh /usr/local/bin/wait-for-it
RUN chmod 555 /usr/local/bin/wait-for-it

# php configs
COPY ./php-fpm.conf  /usr/local/etc/php-fpm.conf
COPY ./conf.d  /usr/local/etc/php/conf.d

# GID and UID mapping for www-data user
ARG WWW_DATA_UID=33
ARG WWW_DATA_GID=33

RUN usermod -u $WWW_DATA_UID www-data && groupmod -g $WWW_DATA_GID www-data

USER www-data
WORKDIR /var/www/app

CMD ["php-fpm", "-R"]