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
    $message = $this->db->query('SELECT * FROM messagerie');
    $response->getBody()->write(json_encode($message));
    return $response;
  }

  //Permet d'obtenir les informations d'un message
  public function getAMessage(RequestInterface $request, ResponseInterface $response, array $args)
  {
    $id_messageWanted = $args['id'];
    $message = $this->db->query('SELECT * FROM messagerie WHERE id_message = $id_messageWanted');
    $response->getBody()->write(json_encode($message));
    return $response;
  }

  //Permet d'ajouter un message
  public function postMessage(RequestInterface $request, ResponseInterface $response, array $args){
    $id_messageWanted = $args['id'];
    $date_messageWanted = $args['date'];
    $content_messageWanted = $args['content'];
    $id_userWanted = $args['id_user'];
    $id_user1Wanted = $args['id_user1'];
    $message = $this->db->query("UPDATE messagerie SET id_message='$id_messageWanted', date_message='$date_messageWanted', content_message='$content_messageWanted', id_user='$id_userWanted', id_user1='$id_user1Wanted';");
    $response->getBody()->write(json_encode($message));
    return $response;
  }

  //Permet de supprimer un message
  public function deleteMessage(RequestInterface $request, ResponseInterface $response, array $args){
    $id_messageWanted = $args['id'];
    $message = $this->db->query("DELETE FROM messagerie WHERE id_message='$id_messageWanted';");
    $response->getBody()->write(json_encode($message));
    return $response;
  }
}