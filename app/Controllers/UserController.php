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

class UserController extends Controller
{
  public function __construct()
  {
    $this->db = new Database();
  }

  //- `GET /api/user` : Permet d'obtenir les informations de l'utilisateur actuellement connecté
  public function getCurrentUser(Request $request, Response $response, array $args)
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


  //- `GET /api/user/{id}` : Permet d'obtenir les informations d'un utilisateur
  public function getUser(Request $request, Response $response, array $args)
  {
    try {
      $userId = $args['id'] ?? null;

      if (!$userId) {
        throw new Exception("Tous les champs sont obligatoires", 400);
      }

      $user = $this->db->user->getUserById($userId);

      return sendJSON($response, $user, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }


  //`GET /api/users` : Permet d'obtenir la liste des utilisateurs
  public function getAllUser(Request $request, Response $response, array $args)
  {
    try {
      $users = $this->db->user->getAll();

      return sendJSON($response, $users, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }

  //`PUT /api/user/{id}` : Permet de mettre à jour les informations d'un utilisateur
  public function putUser(Request $request, Response $response, array $args)
  {
    try {
      $userId = $args['id'] ?? null;
      $rawdata = file_get_contents("php://input");
      parse_str($rawdata, $data);

      $nom = $data['nom'] ?? null;
      $prenom = $data['prenom'] ?? null;
      $email = $data['email'] ?? null;
      $password = $data['password'] ?? null;
      $numero = $data['numero'] ?? null;

      if (!$userId || !$nom || !$prenom || !$email || !$password || !$numero) {
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
      $userId = $args['id'] ?? null;

      if (!$userId) {
        throw new Exception("Tous les champs sont obligatoires", 400);
      }

      $this->db->user->deleteUserById($userId);

      return sendJSON($response, "L'utilisateur a bien été supprimé", 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }
}
