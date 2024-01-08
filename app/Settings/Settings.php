<?php

namespace App\Settings;

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(dirname(__DIR__, 2) . '/.env.local');


return [
  'settings' => [
    // App Settings
    'app' => [
      'name' => "agriconnect",
      'url' => $_ENV['APP_URL'],
      'basePath' => $_ENV['APP_BASE_PATH'],
    ],

    // Database settings
    'database' => [
      'driver' => $_ENV['DB_DRIVER'],
      'host' => $_ENV['DB_HOST'],
      'database' => $_ENV['DB_NAME'],
      'username' => $_ENV['DB_USER'],
      'password' => $_ENV['DB_PASSWORD'],
      'port' => $_ENV['DB_PORT'],
      'charset' => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix' => '',
    ],

    // jwt settings
    'jwt' => [
      'secret' => $_ENV['JWT_SECRET'],
    ],

    // Google API settings
    "googleApiToken"  => $_ENV['GOOGLE_API_TOKEN'],
  ],
];
