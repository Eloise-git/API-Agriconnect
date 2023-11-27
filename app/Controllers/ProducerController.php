<?php
namespace App\Controllers;

use App\Models\Controller;
use App\Models\Database;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use function App\Lib\sendJSON;
use function App\Lib\sendError;
use function App\Lib\hashPassword;


require_once dirname(__DIR__) . '/Lib/Utils.php';

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

  public function getProducerByName(Request $request, Response $response, array $args)
  {
    try {
      $name = $request->getQueryParams()['name'];
      $name = str_replace('-', ' ', $name);
      $name = ucfirst($name);
      $producerWanted = $this->db->producer->getProducerByName($name);

      return sendJSON($response, $producerWanted, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }

  public function postProducer(Request $request, Response $response, array $args)
  {
    try {
      $data = $request->getParsedBody();        

      $nom = $data['nom'] ?? null;
      $prenom = $data['prenom'] ?? null;
      $email = $data['email'] ?? null;
      $password = $data['password'] ?? null;
      $numero = $data['numero'] ?? null;
      $role = $data['role'] ?? null;

      $desc = $data['desc'] ?? null;
      $payement = $data['payement'] ?? null;
      $name = $data['name'] ?? null;
      $adress = $data['adress'] ?? null;
      $phoneNumber = $numero;
      $category = $data['category'] ?? null;
      $producerId = uniqid();
      $created_At = date('Y-m-d');

      $hashedPassword = hashPassword($password);

      if (!$nom || !$prenom || !$email || !$password || !$numero || !$role || !$desc 
          || !$payement || !$name || !$adress || !$category) {
        throw new Exception("Tous les champs sont obligatoires", 400);
      }

      
      $newuser = $this->db->auth->register($nom, $prenom, $email, $hashedPassword, $numero, $created_At, $role);
      $userId = $newuser['id'];
      $producer = $this->db->producer->postProducer($producerId, $desc, $payement, $name, $adress,
            $phoneNumber, $category, $userId);

      return sendJSON($response, $producer, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
}

  public function putProducer(Request $request, Response $response, array $args)
  {
    try {
      $producer = $request->getAttribute('producer');
      $producerId = $args['id'];

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
          $phoneNumber, $category);

      return sendJSON($response, $producer, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }

  // /producer/search?name=&location=&type=&distance=
  public function searchByNameLocationTypeDistance(Request $request, Response $response, array $args)
  {
    try{
      
      $producer = $request->getAttribute('producer');

      $rawdata = file_get_contents("php://input");
      parse_str($rawdata,$data);
      
      $name = $data['name'] ?? null;
      $location = $data['location'] ?? null;
      $type = $data['type'] ?? null;
      $distance = $data['distance'] ?? null;
      
      $this->db->producer->searchByNameLocationTypeDistance($name, $location, $type, $distance);
      $this->db->producer->calculer_distance($location,$producer['adress_producer']);
      return sendJSON($response, $producer, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }
  
  public function deleteProducer(Request $request, Response $response, array $args)
  {
    try {

      $this->db->producer->deleteProducerById($args['id']);

      return sendJSON($response, "Le producteur a bien été supprimé", 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }
}