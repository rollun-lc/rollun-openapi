FROM php:8.0-cli

RUN apt-get update \
    && apt-get install -y unzip \
    libyaml-dev \
    wget \
    openjdk-17-jre

# Install xdebug
RUN pecl install xdebug-3.0.2 && docker-php-ext-enable xdebug

# Install YAML extension
RUN pecl install yaml-2.2.2 && echo "extension=yaml.so" > /usr/local/etc/php/conf.d/ext-yaml.ini

# Install openapi-generator
RUN mkdir /usr/local/share/java
RUN wget https://repo1.maven.org/maven2/org/openapitools/openapi-generator-cli/4.3.1/openapi-generator-cli-4.3.1.jar -O /usr/local/share/java/openapi-generator-cli.jar
COPY ./openapi-generator /usr/local/bin/openapi-generator
RUN chmod +x /usr/local/bin/openapi-generator

COPY ./conf.d  /usr/local/etc/php/conf.d

WORKDIR /var/www/app
