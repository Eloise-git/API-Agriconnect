<?php
namespace App\controllers;

use App\models\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use function App\lib\sendJSON;
use function App\lib\sendError;

class CommandesController
{
  private $container;

  private $db;

  public function __construct($container)
  {
    $this->container = $container;
    $this->db = new Database();
  }

  public function getAllCommandes(Request $request, Response $response, array $args)
  {
    try {
      $order = $this->db->order->getAllOrder();
      
      return sendJSON($response, $producer, 200);
    } catch (Exception $e) {
      var_dump($e->getCode());
      return sendError($response, $e->getMessage());
    }
  }

  public function getACommande(Request $request, Response $response, array $args)
  {
    try {
      $order = $this->db->order->getAnOrderById($args['id']);
      
      return sendJSON($response, $producer, 200);
    } catch (Exception $e) {
      var_dump($e->getCode());
      return sendError($response, $e->getMessage());
    }
  }

  
  public function postCommande(Request $request, Response $response, array $args)
  {
    try {
      $order = $this->db->order->postOrder($args['id'], $args['status'], $args['date'], $args['payement'],
            $args['id_producer'], $args['id_user']);

      return sendJSON($response, $order, 200);
    } catch (Exception $e) {
      var_dump($e->getCode());
      return sendError($response, $e->getMessage());
    }
  }

  public function putCommande(Request $request, Response $response, array $args)
  {
    try {
      $order = $this->db->order->updateOrderById($args['id'], $args['status'], $args['date'], 
            $args['payement'], $args['id_producer'], $args['id_user']);

      return sendJSON($response, $order, 200);
    } catch (Exception $e) {
      var_dump($e->getCode());
      return sendError($response, $e->getMessage());
    }
  }

  public function deleteCommande(Request $request, Response $response, array $args)
  {
    try {
      $order = $this->db->order->deleteOrderById($args['id']);

      return sendJSON($response, $order, 200);
    } catch (Exception $e) {
      var_dump($e->getCode());
      return sendError($response, $e->getMessage());
    }
  }
}