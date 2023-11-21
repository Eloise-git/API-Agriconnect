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

      $response->getBody()->write(json_encode($producer));
      return $response;
    } catch (Exception $e) {
      return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }

  //Permet de mettre à jour les informations d'un producteur
  public function putProducer(Request $request, Response $response, array $args)
  {
    try {
      $id_producerWanted = $args['id'];
      $desc_producerWanted = $args['desc'];
      $payement_producerWanted = $args['payement'];
      $name_producerWanted = $args['name'];
      $adress_producerWanted = $args['adress'];
      $phoneNumber_producerWanted = $args['phoneNumber'];
      $category_producerWanted = $args['category'];
      $id_userWanted = $args['id_user'];
      $producer = $this->db->query("UPDATE producer SET id_producer='$id_producerWanted', desc_producer='$desc_producerWanted', payement_producer='$payement_orderWanted', name_producer='$name_producerWanted', adress_producer='$adress_producerWanted', phoneNumber_producer='$phoneNumber_producerWanted', category_producer='$category_producerWanted', id_user=' $id_userWanted';");
      $response->getBody()->write(json_encode($producer));
      return $response;
    } catch (Exception $e) {
      return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }

  //Permet de supprimer un producteur
  public function deleteProducer(RequestInterface $request, ResponseInterface $response, array $args)
  {
    try {
      $id_producerWanted = $args['id'];
      $producer = $this->db->query("DELETE FROM producer WHERE id_producer='$id_producerWanted';");
      $response->getBody()->write(json_encode($producer));
      return $response;
    } catch (Exception $e) {
      return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }
}