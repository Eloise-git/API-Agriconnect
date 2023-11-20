<?php
namespace App\controllers;

use App\models\Controller;
use App\models\Database;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use function App\lib\sendJSON;
use function App\lib\sendError;

require_once __DIR__ . '/../lib/utils.php';

class UserController extends Controller
{
  public function __construct()
  {
    $this->db = new Database();
  }

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

}