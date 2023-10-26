<?php
namespace App\controllers;

use App\models\Database;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class MessagerieController
{
  private $container;

  private $db;

  public function __construct($container)
  {
    $this->container = $container;
    $this->db = new Database();
  }

  //Permet d'obtenir la liste des messages
  public function getAllMessages(RequestInterface $request, ResponseInterface $response, array $args)
  {
    try{
        $role = '';

        //Obtention des tokens et vérifications à revoir
      if (substr($header, 0, 7) !== 'Bearer ') {
        return false;
      } else if (substr($header, 0, 7) == $token_producer){
        $role = 'producer';
      } elseif (substr($header, 0, 7) == $token_client) {
        $role = 'client';
      }

        $message = $this->db->query('SELECT * FROM messagerie');
        $response->getBody()->write(json_encode($message));
        return $response;
    }catch (Exception $e) {
      return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }

  //Permet d'obtenir les informations d'un message
  public function getAMessage(RequestInterface $request, ResponseInterface $response, array $args)
  {
    try {
      $id_messageWanted = $args['id'];
      $message = $this->db->query('SELECT * FROM messagerie WHERE id_message = $id_messageWanted');
      $response->getBody()->write(json_encode($message));
      return $response;
    }catch (Exception $e) {
      return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }

  //Permet d'ajouter un message
  public function postMessage(RequestInterface $request, ResponseInterface $response, array $args){
    try{
      $id_messageWanted = $args['id'];
      $date_messageWanted = $args['date'];
      $content_messageWanted = $args['content'];
      $id_userWanted = $args['id_user'];
      $id_user1Wanted = $args['id_user1'];
      $message = $this->db->query("INSERT INTO messagerie VALUES ($id_messageWanted','$date_messageWanted','$content_messageWanted','$id_userWanted','$id_user1Wanted';");
      $response->getBody()->write(json_encode($message));
      return $response;
    }catch (Exception $e) {
      return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }

  //Permet de supprimer un message
  public function deleteMessage(RequestInterface $request, ResponseInterface $response, array $args){
    try {
      $id_messageWanted = $args['id'];
      $message = $this->db->query("DELETE FROM messagerie WHERE id_message='$id_messageWanted';");
      $response->getBody()->write(json_encode($message));
      return $response;
    }catch (Exception $e) {
      return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }
}