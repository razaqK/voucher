<VirtualHost *:80>
    ServerAdmin abdrkasali@gmail.com
    DocumentRoot "/var/www/public"

    ServerName localhost
    SetEnv DB_HOST "<?= getenv("DB_HOST") ?>"
    SetEnv DB_USERNAME "<?= getenv("DB_USERNAME") ?>"
    SetEnv DB_PASSWORD "<?= getenv("DB_PASSWORD") ?>"
    SetEnv DB_NAME "<?= getenv("DB_NAME") ?>"
    SetEnv APPLICATION_ENV "<?= getenv("APPLICATION_ENV") ?>"
    SetEnv REDIS_HOST "<?= getenv("REDIS_HOST") ?>"
    SetEnv REDIS_LIFETIME "<?= getenv("REDIS_LIFETIME") ?>"
    ErrorLog "${APACHE_LOG_DIR}/error.log"
    CustomLog "${APACHE_LOG_DIR}/access.log" common

    <Directory "/var/www">
    Options Indexes FollowSymLinks Includes ExecCGI
    AllowOverride All
    Require all granted
    Order allow,deny
    Allow from all
    </Directory>
</VirtualHost>