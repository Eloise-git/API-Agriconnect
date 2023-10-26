<?php
namespace App\controllers;

use App\models\Database;
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


  public function getToken(Request $request, Response $response, array $args)
  {
    try {
      $key = '';
      //Obtention des tokens et vérifications à revoir
      $token = $request->getHeader('Authorization')[0];
      $token = explode(" ", $token)[1];
      $decoded = JWT::decode($token, new key($key, 'HS256'));

      $response->getBody()->write(json_encode($decoded));
      return $response;
    } catch (Exception $e) {
      return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }

  //Permet d'obtenir la liste des messages
  public function getAllMessages(Request $request, Response $response, array $args)
  {
    try {

      $response->getBody()->write(json_encode($decoded));
      return $response;
    } catch (Exception $e) {
      return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }

  //Permet d'obtenir les informations d'un message
  public function getAMessage(Request $request, Response $response, array $args)
  {
    try {
      $id_messageWanted = $args['id'];
      $message = $this->db->query('SELECT * FROM messagerie WHERE id_message = $id_messageWanted');
      $response->getBody()->write(json_encode($message));
      return $response;
    } catch (Exception $e) {
      return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }

  //Permet d'ajouter un message
  public function postMessage(Request $request, Response $response, array $args)
  {
    try {
      $id_messageWanted = $args['id'];
      $date_messageWanted = $args['date'];
      $content_messageWanted = $args['content'];
      $id_userWanted = $args['id_user'];
      $id_user1Wanted = $args['id_user1'];
      $message = $this->db->query("INSERT INTO messagerie VALUES ($id_messageWanted','$date_messageWanted','$content_messageWanted','$id_userWanted','$id_user1Wanted';");
      $response->getBody()->write(json_encode($message));
      return $response;
    } catch (Exception $e) {
      return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }

  //Permet de supprimer un message
  public function deleteMessage(Request $request, Response $response, array $args)
  {
    try {
      $id_messageWanted = $args['id'];
      $message = $this->db->query("DELETE FROM messagerie WHERE id_message='$id_messageWanted';");
      $response->getBody()->write(json_encode($message));
      return $response;
    } catch (Exception $e) {
      return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }
}