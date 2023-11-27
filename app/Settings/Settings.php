<?php
namespace App\Settings;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load('C:/xampp/htdocs/api-agriconnect/.env.local');



return [
  'settings' => [
    // App Settings
    'app' => [
      'name' => "agriconnect",
      'url' => "http://localhost/api-agriconnect",
    ],

    // Database settings
    'database' => [
      'driver' => "mysql",
      'host' => "127.0.0.1",
      'database' => "agriconnect",
      'username' => "root",
      'password' => "",
      'port' => "3306",
      'charset' => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix' => '',
    ],

    // jwt settings
    'jwt' => [
      'secret' => "sMNQd-vlbGoZHOzNONvffK-C3CJ0WstuyFTinbIrvW-wW-Nz9UfGF8DgCKTgfyHStoQkRCMr442MJrXUSBI8ZA",
    ],
    "googleApiToken"  => $_ENV['GOOGLE_API_TOKEN'],
  ],
];