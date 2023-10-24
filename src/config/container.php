<?php

use Psr\Container\ContainerInterface;
use Selective\Config\Configuration;
use Selective\Database\Connection;
use Slim\App;



return [

    // Database connection
    Connection::class => function (ContainerInterface $container) {
        return new Connection($container->get(PDO::class));
    },

    PDO::class => function (ContainerInterface $container) {
        $settings = $container->get('settings')['db'];

        $driver = $settings['driver'];
        $host = $settings['host'];
        $dbname = $settings['database'];
        $username = $settings['username'];
        $password = $settings['password'];
        $charset = $settings['charset'];
        $flags = $settings['flags'];
        $dsn = "$driver:host=$host;dbname=$dbname;charset=$charset";

        return new PDO($dsn, $username, $password, $flags);
    },
];