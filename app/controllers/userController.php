<?php
namespace App\controllers;

use App\models\Controller;
use App\models\Database;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use function App\lib\sendJSON;
use function App\lib\sendError;
use function App\lib\hashPassword;

require_once __DIR__ . '/../lib/utils.php';

class UserController extends Controller
{
  public function __construct()
  {
    $this->db = new Database();
  }

  //- `GET /api/user/{id}` : Permet d'obtenir les informations d'un utilisateur
  public function getUser(Request $request, Response $response, array $args)
  {
    try {
      $user = $request->getAttribute('user');
      $userId = $user->id;
      
      $user = $this->db->user->getUserById($userId);

      return sendJSON($response, $user, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }
  
  //`PUT /api/user/{id}` : Permet de mettre à jour les informations d'un utilisateur
  public function putUser(Request $request, Response $response, array $args)
  {
    try {
      $user = $request->getAttribute('user');
      $userId = $user->id;
      $rawdata = file_get_contents("php://input");
      parse_str($rawdata,$data);
      
      $nom = $data['nom'] ?? null;
      $prenom = $data['prenom'] ?? null;
      $email = $data['email'] ?? null;
      $password = $data['password'] ?? null;
      $numero = $data['numero'] ?? null;
      
      if (!$nom || !$prenom || !$email || !$password || !$numero) {
        throw new Exception("Tous les champs sont obligatoiress", 400);
        
      }

      $hashedPassword = hashPassword($password);
      $user = $this->db->user->updateUserById($userId, $nom, $prenom, $email, $hashedPassword, $numero);

      return sendJSON($response, $user, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }
  
  //DELETE /api/user/{id}` : Permet de supprimer un utilisateur
  public function deleteUser(Request $request, Response $response, array $args)
  {
    try {
      $user = $request->getAttribute('user');
      $userId = $user->id;

      $this->db->user->deleteUserById($userId);

      return sendJSON($response, "L'utilisateur a bien été supprimé", 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }

}