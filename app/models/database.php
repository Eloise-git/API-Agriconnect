<?php

namespace App\models;

use PDO;

class Database
{

  private $pdo;

  public function __construct()
  {
    $this->pdo = new PDO('mysql:host=localhost;dbname=agriconnect', 'root', '');
  }

  public function query($query)
  {
    $req = $this->pdo->prepare($query);
    $req->execute();
    return $req->fetchAll(PDO::FETCH_ASSOC);
  }

}