FROM php:7.4-cli

RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y vim git nano \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN git clone https://github.com/danielribes/kataSnakesAndLadders.git /usr/local/src

WORKDIR /usr/local/src
