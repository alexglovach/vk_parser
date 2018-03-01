<?php return [
    'mysql' => [
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'database' => 'test_vk_parser',
        'charset' => 'utf8',
        'username' => 'root',
        'password' => 'root',
        'options' => [
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ERRMODE_EXCEPTION,
        ],
    ],
    'rabbitmq' => [
        'host' => '127.0.0.1',
        'port' => '5672',
        'vhost' => '/',
        'login' => 'test',
        'password' => 'test',
    ],
];