<?php

namespace App\models;

use PDO;


class Database
{

  private $pdo;

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

    $this->pdo = new PDO($dsn, $username, $password);
  }

  public function query($query)
  {
    $req = $this->pdo->prepare($query);
    $req->execute();
    return $req->fetchAll(PDO::FETCH_ASSOC);
  }

}