<?php
namespace App\controllers;

use App\models\Database;
use Psr\Http\Commandes\RequestInterface;
use Psr\Http\Commandes\ResponseInterface;

class CommandesController
{
  private $container;

  private $db;

  public function __construct($container)
  {
    $this->container = $container;
    $this->db = new Database();
  }

  //Permet d'obtenir la liste des commandes
  public function getAllCommandes(RequestInterface $request, ResponseInterface $response, array $args)
  {
    try {
      $order = $this->db->query('SELECT * FROM commande');
      $response->getBody()->write(json_encode($order));
      return $response;
    } catch (Exception $e) {
      return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }

  //Permet d'obtenir les informations d'une commande
  public function getACommande(RequestInterface $request, ResponseInterface $response, array $args)
  {
    try {
      $id_orderWanted = $args['id'];
      $order = $this->db->query("SELECT * FROM commande WHERE id_commande ='$id_commandeWanted");
      $response->getBody()->write(json_encode($order));
      return $response;
    } catch (Exception $e) {
      return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }

  //Permet d'ajouter une commande
  public function postCommande(RequestInterface $request, ResponseInterface $response, array $args)
  {
    try {
      $id_orderWanted = $args['id'];
      $status_orderWanted = $args['status'];
      $date_orderWanted = $args['date'];
      $payement_orderWanted = $args['payement'];
      $id_producer_orderWanted = $args['id_producer'];
      $id_user_orderWanted = $args['id_user'];
      $order = $this->db->query("INSERT INTO commande VALUES ($id_orderWanted','$status_orderWanted','$date_orderWanted','$payement_orderWanted','$id_producer_orderWanted','$id_user_orderWanted';");
      $response->getBody()->write(json_encode($order));
      return $response;
    } catch (Exception $e) {
      return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }

  //Permet de mettre à jour les informations d'une commande
  public function putCommande(RequestInterface $request, ResponseInterface $response, array $args)
  {
    try {
      $id_orderWanted = $args['id'];
      $status_orderWanted = $args['status'];
      $date_orderWanted = $args['date'];
      $payement_orderWanted = $args['payement'];
      $id_producer_orderWanted = $args['id_producer'];
      $id_user_orderWanted = $args['id_user'];
      $order = $this->db->query("UPDATE commande SET id_order='$id_orderWanted', status_order='$status_orderWanted', date_order='$date_orderWanted', payement_order='$payement_orderWanted', id_producer='$id_producer_orderWanted', id_user='$id_user_orderWanted';");
      $response->getBody()->write(json_encode($order));
      return $response;
    } catch (Exception $e) {
      return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }

  //Permet de supprimer une commande
  public function deleteCommande(RequestInterface $request, ResponseInterface $response, array $args)
  {
    try {
      $id_orderWanted = $args['id'];
      $order = $this->db->query("DELETE FROM commande WHERE id_order='$id_orderWanted';");
      $response->getBody()->write(json_encode($order));
      return $response;
    } catch (Exception $e) {
      return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }
}