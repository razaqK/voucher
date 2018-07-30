#!/usr/bin/env bash

php -f docker/vhost.conf.php > /etc/apache2/sites-available/000-default.conf

chown -R www-data:www-data /var/www

mkdir -p /var/log/application && \
chown -R www-data:www-data /var/log/application && \
chmod -R 750 /var/log/application
mkdir -p /var/log/apache2 && \
touch /var/log/apache2/access.log && \
chown -R root:adm /var/log/apache2 && \
chmod -R 750 /var/log/apache2
touch /var/log/apache2/access.log

composer install

service apache2 start

tail -f /var/log/apache2/access.log