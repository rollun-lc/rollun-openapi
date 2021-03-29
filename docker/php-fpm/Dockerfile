FROM maxrollundev/php-fpm-7.2

USER root

# Install ping and ip command for entrypoint.sh
RUN apt-get update \
    && apt-get install -y iputils-ping \
    iproute2 \
    libyaml-dev

# Install xdebug
RUN pecl install xdebug-3.0.2 && docker-php-ext-enable xdebug

# Install YAML extension
RUN pecl install yaml-2.0.2 && echo "extension=yaml.so" > /usr/local/etc/php/conf.d/ext-yaml.ini

COPY ./php-fpm.conf  /usr/local/etc/php-fpm.conf
COPY ./conf.d  /usr/local/etc/php/conf.d

# Set entrypoint
COPY ./entrypoint.sh /usr/local/bin/docker-php-entrypoint
RUN chmod +x /usr/local/bin/docker-php-entrypoint

WORKDIR /var/www/app

CMD ["php-fpm", "-R"]