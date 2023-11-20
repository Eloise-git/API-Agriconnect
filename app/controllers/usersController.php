<?php
namespace App\controllers;

use App\models\Controller;
use App\models\Database;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use function App\lib\sendJSON;

require_once __DIR__ . '/../lib/utils.php';

class UsersController extends Controller
{
  public function __construct()
  {
    $this->db = new Database();
  }

  // EndPoint de test
  public function home(Request $request, Response $response, array $args)
  {
    try {
      $users = $this->db->query("SELECT * FROM utilisateur");

      return sendJSON($response, $users, 200);
    } catch (Exception $e) {
      return sendJSON($response, $e->getMessage(), 500);
    }
  }

  public function getUser(Request $request, Response $response, array $args)
  {
    try {
      $user = $request->getAttribute('user');

      $userId = $user->id;
      $sql = "SELECT * FROM utilisateur WHERE id_user = '$userId'";
      $user = $this->db->query($sql)[0];

      $res = [
        "id" => $user['id_user'],
        "name" => $user['firstName_user'],
        "surname" => $user['lastName_user'],
        "email" => $user['email_user'],
        "phone" => $user['phoneNumber_user'],
        "role" => $user['role_user'],
        "createdAt" => $user['createdAt_user']
      ];

      return sendJSON($response, $res, 200);
    } catch (Exception $e) {
      return sendJSON($response, $e->getMessage(), 500);
    }
  }

}