FROM maxrollundev/php-fpm-7.2

USER root

# Архивные репы для buster
RUN sed -i 's|deb.debian.org/debian|archive.debian.org/debian|g' /etc/apt/sources.list \
 && sed -i 's|security.debian.org/debian-security|archive.debian.org/debian-security|g' /etc/apt/sources.list \
 && printf 'Acquire::Check-Valid-Until "false";\nAcquire::AllowInsecureRepositories "true";\n' > /etc/apt/apt.conf.d/99archive

# Install ping and ip command for entrypoint.sh
RUN apt-get update \
    && apt-get install -y iputils-ping \
    iproute2 \
    libyaml-dev

# Install xdebug
RUN pecl install xdebug-3.0.2 && docker-php-ext-enable xdebug

# Install YAML extension
RUN pecl install yaml && docker-php-ext-enable yaml

RUN composer self-update

COPY ./php-fpm.conf  /usr/local/etc/php-fpm.conf
COPY ./conf.d  /usr/local/etc/php/conf.d

# Set entrypoint
COPY ./entrypoint.sh /usr/local/bin/docker-php-entrypoint
RUN chmod +x /usr/local/bin/docker-php-entrypoint

WORKDIR /var/www/app

CMD ["php-fpm", "-R"]