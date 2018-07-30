FROM php:7.2-apache

# Installing dependencies
RUN apt-get update && apt-get upgrade -y
RUN apt-get -y install \
    bzip2 \
    freetds-dev \
    git \
    libfontconfig \
    libfreetype6-dev \
    libicu-dev \
    libjpeg-dev \
    libldap2-dev \
    libmcrypt-dev \
    default-libmysqlclient-dev \
    libpng-dev \
    libpq-dev \
    libwebp-dev \
    libxml2-dev \
    zlib1g-dev \
    zip \
    vim \
    wget \
    unzip \
    curl \
    cron

RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install redis-3.1.2 \
    && pecl install xdebug-2.6.0 \
    && docker-php-ext-enable redis xdebug

RUN apt-get install -y libxml2-dev && \
    docker-php-ext-install soap

RUN cd /tmp && \
    git clone --depth=1 --branch v3.2.2 "git://github.com/phalcon/cphalcon.git" && \
    cd cphalcon/build && \
    ./install && \
    echo "extension=phalcon.so" > /usr/local/etc/php/conf.d/phalcon.ini

RUN a2enmod rewrite

# Download and install Composer
RUN php -r "readfile('https://getcomposer.org/installer');" | php &&\
    mv composer.phar /usr/bin/composer && chmod +x /usr/bin/composer

WORKDIR /var/www

COPY . /var/www

RUN composer update

RUN chown -R www-data:www-data /var/www

COPY ./docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]