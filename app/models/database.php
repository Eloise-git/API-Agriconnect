<?php

namespace App\models;

use App\services\AuthService;
use App\services\UserService;
use App\services\ProducerService;
use App\services\ProductService;
use PDO;

class Database
{
  private PDO $db;
  public $auth;
  public $user;
  public $producer;
  public $product;

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
    $this->user = new UserService($this->db);
    $this->producer = new ProducerService($this->db);
    $this->product = new ProductService($this->db);
  }
}