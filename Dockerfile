FROM php:7.4-cli

# Actualitzar/instal·lar paquets
RUN apt-get update && \
    apt-get install -y git libzip-dev zip && \
    docker-php-ext-install zip && \
    rm -rf /var/lib/apt/lists/*

# Instal·lar composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer