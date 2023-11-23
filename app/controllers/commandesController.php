<?php
namespace App\controllers;

use Exception;
use App\models\Database;
use App\models\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use function App\lib\sendJSON;
use function App\lib\sendError;

require_once __DIR__ . '/../lib/utils.php';

class CommandesController extends Controller

{public function __construct()
  {
    $this->db = new Database();
  }

  public function getAllCommandes(Request $request, Response $response, array $args)
  {
    try {
      $order = $this->db->order->getAllOrders();
      
      return sendJSON($response, $order, 200);
    } catch (Exception $e) {
      var_dump($e->getCode());
      return sendError($response, $e->getMessage());
    }
  }

  public function getACommande(Request $request, Response $response, array $args)
  {
    try {
      $order = $this->db->order->getAnOrderById($args['id']);
      
      return sendJSON($response, $order, 200);
    } catch (Exception $e) {
      var_dump($e->getCode());
      return sendError($response, $e->getMessage());
    }
  }

  
  public function postCommande(Request $request, Response $response, array $args)
  {
    try {
      $data = $request->getParsedBody(); 

      $status = $data['status'] ?? null;
      $date = $data['date'] ?? null;
      $payement = $data['payement'] ?? null;
      $id_producer = $data['id_producer'] ?? null;
      $id_user = $data['id_user'] ?? null;
      $id_order = uniqid();

      if (!$status || !$date || !$payement || !$id_producer || !$id_user) {
        throw new Exception("Tous les champs sont obligatoires", 400);
      }

      $order = $this->db->order->postOrder($id_order, $status, $date, $payement, $id_producer, $id_user);

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