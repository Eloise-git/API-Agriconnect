<?php

namespace App\Controllers;

use App\Models\Controller;
use App\Models\Database;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use function App\Lib\sendJSON;
use function App\Lib\sendError;

require_once dirname(__DIR__) . '/Lib/Utils.php';
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
      $id_message = $args['id'] ?? null;

      if (!$id_message) {
        throw new Exception("Tous les champs sont obligatoires", 400);
      }

      $message = $this->db->message->getAMessageById($id_message);

      return sendJSON($response, $message, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }

  public function getAMessageByUserId(Request $request, Response $response, array $args)
  {
    try {
      $id_user = getCurrentUser();

      $message = $this->db->message->getAMessageByUserId($id_user);

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
      $content = $body['message'];
      $id_user1 = $body['destinataire'];

      if (!$id_user1 || !$content) {
        throw new Exception("Tous les champs sont obligatoires", 400);
      }

      $idmes = uniqid();
      $date = date('Y-m-d H:i:s');

      $message = $this->db->message->postMessage($idmes, $date, $content, $userId, $id_user1);

      return sendJSON($response, $message, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }

  public function deleteMessage(Request $request, Response $response, array $args)
  {
    try {
      $id_message = $args['id'] ?? null;

      if (!$id_message) {
        throw new Exception("Tous les champs sont obligatoires", 400);
      }

      $this->db->message->deleteMessageById($id_message);

      return sendJSON($response, "Le message a bien été supprimer", 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }
}
