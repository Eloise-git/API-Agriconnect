<?php
namespace App\controllers;

use App\models\Controller;
use App\models\Database;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use function App\lib\send;

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

      return send($response, $users, false, 200);
    } catch (Exception $e) {
      return send($response, $e->getMessage(), true, 500);
    }
  }

  public function getUser(Request $request, Response $response, array $args)
  {
    try {
      $settings = require __DIR__ . '/../settings/settings.php';
      $key = $settings['settings']['jwt']['secret'];

      $token = $request->getHeader('Authorization')[0];
      $token = explode(" ", $token)[1];
      $decoded = JWT::decode($token, new key($key, 'HS256'));

      $id = $decoded->data->id;
      $sql = "SELECT * FROM utilisateur WHERE id_user = '$id'";
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

      return send($response, $res, false, 200);
    } catch (Exception $e) {
      return send($response, $e->getMessage(), true, 500);
    }
  }

}