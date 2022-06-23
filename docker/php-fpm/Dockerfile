FROM maxrollundev/php-fpm-8.0

USER root

# Install ping and ip command for entrypoint.sh
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

# Install xdebug
RUN pecl install xdebug-3.0.2 && docker-php-ext-enable xdebug

# Install YAML extension
RUN pecl install yaml-2.2.2 && echo "extension=yaml.so" > /usr/local/etc/php/conf.d/ext-yaml.ini

COPY ./php-fpm.conf  /usr/local/etc/php-fpm.conf
COPY ./conf.d  /usr/local/etc/php/conf.d

# Set entrypoint
COPY ./entrypoint.sh /usr/local/bin/docker-php-entrypoint
RUN chmod +x /usr/local/bin/docker-php-entrypoint

WORKDIR /var/www/app

CMD ["php-fpm", "-R"]
