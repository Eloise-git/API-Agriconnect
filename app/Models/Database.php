<?php
namespace App\Models;

use App\Services\AuthService;
use App\Services\UserService;
use App\Services\ProducerService;
use App\Services\ProductService;
use App\Services\CommandeService;
use App\Services\MessagerieService;
use App\Services\StockService;
use PDO;

class Database
{
  private PDO $db;
  public $auth;
  public $user;
  public $producer;
  public $product;
  public $order;
  public $message;
  public $stock;

  public function __construct()
  {
    $settings = require dirname(__DIR__) . '/Settings/Settings.php';
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
    $this->order = new CommandeService($this->db);
    $this->message = new MessagerieService($this->db);
    $this->stock = new StockService($this->db);
  }
}
