<?php
namespace App\controllers;

use App\models\Database;
use App\models\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use function App\lib\sendJSON;
use function App\lib\sendError;

require_once __DIR__ . '/../lib/utils.php';

class ProducerController extends Controller
{
  public function __construct()
  {
    $this->db = new Database();
  }
  public function getAllProducer(Request $request, Response $response, array $args)
  {
    try {
      $producer = $this->db->producer->getAllProducer();

      return sendJSON($response, $producer, 200);
    } catch (Exception $e) {
      var_dump($e->getCode());
      return sendError($response, $e->getMessage());
    }
  }

    public function getProducerById(Request $request, Response $response, array $args)
  {
    try {
      $producerWanted = $this->db->producer->getProducerById($args['id']);
      return sendJSON($response, $producerWanted, 200);
    } catch (Exception $e) {
      var_dump($e->getCode());
      return sendError($response, $e->getMessage());
    }
  }

  public function postProducer(Request $request, Response $response, array $args)
  {
    try {
      $producerToInsert = $this->db->producer->postProducer($args['desc'], $args['payement'],
          $args['name'], $args['adress'], $args['phoneNumber'], $args['category']);
      return sendJSON($response, $producerToInsert, 200);
    } catch (Exception $e) {
      var_dump($e->getCode());
      return sendError($response, $e->getMessage());
    }
  }

  public function putProducer(Request $request, Response $response, array $args)
  {
    try {
      $producer = $request->getAttribute('producer');
      $producerId = $producer->id;
      $producerId_user = $producer->id_user;
      $rawdata = file_get_contents("php://input");
      parse_str($rawdata,$data);
      
      $desc = $data['desc'] ?? null;
      $payement = $data['payement'] ?? null;
      $name = $data['name'] ?? null;
      $adress = $data['adress'] ?? null;
      $phoneNumber = $data['phoneNumber'] ?? null;
      $category = $data['category'] ?? null;
      
      if (!$desc || !$payement || !$name || !$adress || !$phoneNumber || !$category) {
        throw new Exception("Tous les champs sont obligatoires", 400);
      }
      $producer = $this->db->producer->updateProducerById($producerId, $desc, $payement, $name, $adress,
          $phoneNumber, $category, $producerId_user);

      return sendJSON($response, $producer, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }

  public function deleteProducer(Request $request, Response $response, array $args)
  {
    try {
      $producer = $request->getAttribute('producer');
      $producerId = $producer->id;

      $this->db->producer->deleteProducerById($producerId);

      return sendJSON($response, "Le producteur a bien été supprimé", 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }
}