FROM php:7.4-apache
ARG DEBIAN_FRONTEND=noninteractive
RUN apt update && apt install -y curl \
  && curl -sS https://getcomposer.org/installer | php \
  && chmod +x composer.phar \
  && mv composer.phar /usr/local/bin/composer
RUN docker-php-ext-install mysqli
RUN apt-get update \
  && apt-get install -y libzip-dev \
  && apt-get install -y zlib1g-dev \
  && rm -rf /var/lib/apt/lists/* \
  && docker-php-ext-install zip
RUN docker-php-ext-install pdo pdo_mysql
# RUN apt-get update && apt-get install -y \
#   libfreetype6-dev \
#   libjpeg62-turbo-dev \
#   libpng-dev \
#   && docker-php-ext-configure gd --with-freetype --with-jpeg \
#   && docker-php-ext-install -j$(nproc) gd
# COPY resources/000-default.conf /etc/apache2/sites-available/
RUN a2enmod rewrite
# RUN mkdir /var/www/public
RUN service apache2 restart
