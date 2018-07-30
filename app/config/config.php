<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 7/28/18
 * Time: 6:45 PM
 */

return new \Phalcon\Config([
    'app_name' => 'voucher',
    'app_url' => getenv('APP_URL'),
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => getenv('DB_HOST'),
        'username'    => getenv('DB_USERNAME'),
        'password'    => getenv('DB_PASSWORD'),
        'dbname'      => getenv('DB_NAME'),
        'charset'     => 'utf8',
    ],

    'application' => [
        'constantsDir'   => __DIR__ . '/../../app/constants/',
        'controllersDir' => __DIR__ . '/../../app/controllers/',
        'modelsDir'      => __DIR__ . '/../../app/models/',
        'libraryDir'     => __DIR__ . '/../../app/library/',
        'traitDir'       => __DIR__ . '/../../app/traits/',
        'routesDir'       => __DIR__ . '/../../app/routes/',
        'observerDir'       => __DIR__ . '/../../app/observers/',
        'observerConfigDir'       => __DIR__ . '/../../app/observers/config',
        'baseUri'        => '/'
    ]
]);