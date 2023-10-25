<?php
namespace App\controllers;

use App\models\Database;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class UsersController
{
  private $container;

  private $db;

  public function __construct($container)
  {
    $this->container = $container;
    $this->db = new Database();
  }

  public function home(RequestInterface $request, ResponseInterface $response, array $args)
  {
    $users = $this->db->query('SELECT * FROM utilisateur');
    $response->getBody()->write(json_encode($users));
    return $response;
  }
}