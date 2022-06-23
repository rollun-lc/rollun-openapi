FROM maxrollundev/php-fpm-dev-7.2

# To fix dpkg error while installing default-jre: https://yarnaudov.com/fix-java-jdk-jre-debian-ubuntu-install.html
RUN mkdir -p /usr/share/man/man1

# Install yaml and java
RUN apt-get update \
    && apt-get install -y libyaml-dev \
    wget \
    default-jre

# Install openapi-generator
RUN mkdir /usr/local/share/java
RUN wget https://repo1.maven.org/maven2/org/openapitools/openapi-generator-cli/4.3.1/openapi-generator-cli-4.3.1.jar -O /usr/local/share/java/openapi-generator-cli.jar
COPY ./openapi-generator /usr/local/bin/openapi-generator
RUN chmod +x /usr/local/bin/openapi-generator

# Install YAML extension
RUN docker-php-ext-enable yaml

ENV COMPOSER_MEMORY_LIMIT -1
