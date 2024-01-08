<?php

namespace App\Controllers;

use App\Models\Controller;
use App\Models\Database;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use function App\Lib\sendJSON;
use function App\Lib\sendError;
use function App\Lib\generateToken;
use function App\Lib\hashPassword;

require_once dirname(__DIR__) . '/Lib/Utils.php';

class AuthController extends Controller
{
  public function __construct()
  {
    $this->db = new Database();
  }

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
      return sendError($response, $e->getMessage());
    }
  }

  public function register(Request $request, Response $response, array $args)
  {
    try {
      $data = $request->getParsedBody();

      $nom = $data['name'] ?? null;
      $prenom = $data['surname'] ?? null;
      $email = $data['email'] ?? null;
      $password = $data['password'] ?? null;
      $numero = $data['phone'] ?? null;
      $role = $data['role'] ?? null;

      if (!$nom || !$prenom || !$email || !$password || !$numero || !$role) {
        throw new Exception("Tous les champs sont obligatoires", 400);
      }

      $hashedPassword = hashPassword($password);
      $createdAt = date('Y-m-d');
      $newuser = $this->db->auth->register($nom, $prenom, $email, $hashedPassword, $numero, $createdAt, $role);

      return sendJSON($response, $newuser, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage(), $e->getCode());
    }
  }
}
