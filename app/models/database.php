<?php

namespace App\models;

use App\models\DatabaseConnector;
use App\services\AuthService;
use PDO;


class Database
{
  private PDO $db;
  public $auth;

  public function __construct()
  {
    $settings = require __DIR__ . '/../settings/settings.php';

    $dbSettings = $settings['settings']['database'];

    $host = $dbSettings['host'];
    $dbname = $dbSettings['database'];
    $username = $dbSettings['username'];
    $password = $dbSettings['password'];
    $charset = $dbSettings['charset'];

    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

    $this->db = new PDO($dsn, $username, $password);
    $this->auth = new AuthService($this->db);
  }
}