<?php
namespace App\Controllers;

use App\Models\Controller;
use App\Models\Database;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use function App\Lib\sendJSON;
use function App\Lib\sendError;

require_once dirname(__DIR__) . '/Lib/Utils.php';

class CommandesController extends Controller

{public function __construct()
  {
    $this->db = new Database();
  }

  public function getAllCommandes(Request $request, Response $response, array $args)
  {
    try {
      $user = $request->getAttribute('user');
      $userId=$user->id;

      $id_producer = $this->db->producer->getProducerByUserId($userId)[0]['id_producer'];
      $order = $this->db->order->getAllOrders($id_producer);
      
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
      $listProducts = $data['listProducts'];

      if (!$status || !$date || !$payement || !$id_producer || !$id_user || !$listProducts) {
        throw new Exception("Tous les champs sont obligatoires", 400);
      }

      $order = $this->db->order->postOrder($id_order, $status, $date, $payement, $id_producer, $id_user, $listProducts);

      return sendJSON($response, $order, 200);
    } catch (Exception $e) {
      var_dump($e->getCode());
      return sendError($response, $e->getMessage());
    }
  }

  public function putCommande(Request $request, Response $response, array $args)
  {
    try {

      $id_order = $args['id'];
      $rawdata = file_get_contents("php://input");
      parse_str($rawdata,$data);
      
      $status = $data['status'] ?? null;
      $date = $data['date'] ?? null;
      $payement = $data['payement'] ?? null;
      $id_producer = $data['id_producer'] ?? null;
      $id_user = $data['id_user'] ?? null;

      $order = $this->db->order->updateOrderById($id_order, $status, $date, $payement, $id_producer, $id_user);

      return sendJSON($response, $order, 200);
    } catch (Exception $e) {
      var_dump($e->getCode());
      return sendError($response, $e->getMessage());
    }
  }

  public function deleteCommande(Request $request, Response $response, array $args)
  {
    try {
      
      $id_order = $args['id'];

      $this->db->order->deleteOrderById($id_order);

      return sendJSON($response, "La commande a bien été supprimée", 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }
}