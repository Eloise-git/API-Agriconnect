<?php
namespace App\settings;

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
      'secret' => "NyzYCdx0L05e0Zwau08UDrKu4VtFcr5sxsvfJSOSi/Y=",
    ],
  ],
];