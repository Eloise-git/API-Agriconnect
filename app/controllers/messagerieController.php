<?php
namespace App\controllers;

use App\models\Database;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class MessagerieController
{
  private $container;

  private $db;

  public function __construct($container)
  {
    $this->container = $container;
    $this->db = new Database();
  }

  public function getAllMessages(Request $request, Response $response, array $args)
  {
    try {
      $message = $this->db->message->getAllMessages();

      return sendJSON($response, $message, 200);
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
      $message = $this->db->message->postMessage($args['id'], $args['date'], $args['content'], $args['id_user'], $args['id_user1']);

      return sendJSON($response, $message, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }

  public function deleteMessage(Request $request, Response $response, array $args)
  {
    try {
      $message = $this->db->message->deleteMessage($args['id']);

      return sendJSON($response, $message, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }
}