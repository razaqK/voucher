<?php
/**
 * Phinx Config file
 *
 */
$name = getenv('DB_NAME');
$user = getenv('DB_USERNAME');
$pass = getenv('DB_PASSWORD');
$host = getenv('DB_HOST');

return [
    'paths' => [
        'migrations' => __DIR__ . '/data/migrations',
        'seeds' => __DIR__ . '/data/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'application',
        'application' => [
            'adapter' => 'mysql',
            'name' => $name,
            'user' => $user,
            'pass' => $pass,
            'host' => $host,
            'port' => 3306
        ],
    ]
];