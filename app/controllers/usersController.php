<?php
namespace App\controllers;

use App\models\Controller;
use App\models\Database;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
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

}