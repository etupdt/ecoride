FROM php:8.3-apache AS apache

SHELL ["/bin/bash", "-c"]

ARG HTTP_APACHE_PORT
ARG HTTPS_APACHE_PORT
ENV PORT=$HTTP_APACHE_PORT

RUN curl --silent --location https://deb.nodesource.com/setup_lts.x
RUN apt-get update && apt-get upgrade -y && apt-get install -yq nodejs npm
RUN apt-get install vim -y
RUN apt-get install traceroute -y
RUN apt-get install iputils-ping -y
RUN apt-get install net-tools -y
RUN apt-get install locate -y
RUN apt-get install ca-certificates -y
RUN apt-get install zip -y
RUN apt-get install unzip -y
RUN apt-get install -y curl

RUN pecl install mongodb

RUN docker-php-ext-enable mongodb

RUN apt-get install -y libpq-dev libicu-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install intl pdo pdo_pgsql pgsql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN export COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html/config/ssl/back

COPY ./ .

WORKDIR /etc/apache2/sites-available/back

COPY ./ .

RUN mkdir -p /var/www/html/ecoride

WORKDIR /var/www/html/ecoride

COPY --chown=www-data:www-data ./EcoRide ./

RUN composer update --no-dev --optimize-autoloader

RUN echo "Include /etc/apache2/sites-available/back/httpd-vhosts.conf" | tee -a /etc/apache2/sites-available/000-default.conf > /dev/null
RUN sed -i 's/;extension=pdo_pgsql/extension=pdo_pgsql/g' /usr/local/etc/php/php.ini-production
RUN sed -i 's/;extension=pdo_pgsql/extension=pdo_pgsql/g' /usr/local/etc/php/php.ini-development

RUN a2enmod env
RUN a2enmod macro
RUN a2enmod rewrite
RUN a2enmod ssl
RUN a2enmod headers

RUN node -v
RUN npm -v

RUN composer update --no-dev --optimize-autoloader
RUN npm update
RUN php bin/console sass:build
RUN php bin/console asset-map:compile

RUN chown -R www-data:www-data /var/www/html

EXPOSE 8080
EXPOSE 9443