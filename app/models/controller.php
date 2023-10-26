<?php
namespace App\models;

class Controller
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }
}