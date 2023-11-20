<?php
namespace App\controllers;

use App\models\Controller;
use App\models\Database;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use function App\lib\sendJSON;
use function App\lib\sendError;
use function App\lib\generateToken;
use function App\lib\hashPassword;


require_once __DIR__ . '/../lib/utils.php';

class AuthController extends Controller
{
  public function __construct()
  {
    $this->db = new Database();
  }

  /**
   * Fonction de connexion de l'utilisateur
   *
   * @author Antoine
   */
  public function login(Request $request, Response $response, array $args)
  {
    try {
      $data = $request->getParsedBody();

      $email = $data['email'] ?? null;
      $password = $data['password'] ?? null;

      if (!$email || !$password) {
        throw new Exception("Tous les champs sont obligatoires", 400);
      }

      $hashedPassword = hashPassword($password);
      $user = $this->db->auth->login($email, $hashedPassword);

      $jwt = generateToken($user['id'], $user['role']);
      $res = [
        'id' => $user['id'],
        'role' => $user['role'],
        'accessToken' => $jwt
      ];

      return sendJSON($response, $res, 200);
    } catch (Exception $e) {
      var_dump($e->getCode());
      return sendError($response, $e->getMessage());
    }
  }

  /**
   * Enregistre un nouvel utilisateur dans la base de données.
   * 
   * @author Antoine
   */
  public function register(Request $request, Response $response, array $args)
  {
    try {
      $data = $request->getParsedBody();

      $nom = $data['nom'] ?? null;
      $prenom = $data['prenom'] ?? null;
      $email = $data['email'] ?? null;
      $password = $data['password'] ?? null;
      $numero = $data['numero'] ?? null;
      $role = $data['role'] ?? null;

      if (!$nom || !$prenom || !$email || !$password || !$numero || !$role) {
        throw new Exception("Tous les champs sont obligatoires", 400);
      }

      $hashedPassword = hashPassword($password);
      $newuser = $this->db->auth->register($nom, $prenom, $email, $hashedPassword, $numero, $role);

      return sendJSON($response, $newuser, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage(), $e->getCode());
    }
  }

}