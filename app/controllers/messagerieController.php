<?php
namespace App\Controllers;

use App\Models\Controller;
use App\Models\Database;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use function App\Lib\sendJSON;
use function App\Lib\sendError;

require_once __DIR__ . '/../lib/utils.php';
class MessagerieController extends Controller
{
  public function __construct()
  {
    $this->db = new Database();
  }

  public function getAllMessages(Request $request, Response $response, array $args)
  {
    try {

      $messages = $this->db->message->getAllMessages();

      return sendJSON($response, $messages, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }

  public function getAMessage(Request $request, Response $response, array $args)
  {
    try {

      $message = $this->db->message->getAMessageById($args['id']);

      return sendJSON($response, $message, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }

  public function postMessage(Request $request, Response $response, array $args)
  {
    try {
      
      $user = $request->getAttribute('user');
      $userId = $user->id;

      $body = $request->getParsedBody();
      $idmes = uniqid();
      $date = date('Y-m-d H:i:s');
      $content = $body['message'];
      $id_user1 = $body['destinataire'];

      if (!$id_user1 || !$content) {
        throw new Exception("Tous les champs sont obligatoires", 400);
      }
      
      $message = $this->db->message->postMessage($idmes, $date, $content, $userId, $id_user1);

      return sendJSON($response, $message, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }

  public function deleteMessage(Request $request, Response $response, array $args)
  {
    try {
      $this->db->message->deleteMessageById($args['id']);
      return sendJSON($response, "Le message a bien été supprimer", 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }
}