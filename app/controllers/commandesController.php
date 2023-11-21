<?php
namespace App\controllers;

use App\models\Database;
use App\models\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use function App\lib\sendJSON;
use function App\lib\sendError;

class CommandesController
{
  private $container;

  private $db;

  public function __construct($container)
  {
    $this->container = $container;
    $this->db = new Database();
  }

  public function getAllCommandes(Request $request, Response $response, array $args)
  {
    try {
      $order = $this->db->order->getAllOrder();
      
      return sendJSON($response, $producer, 200);
    } catch (Exception $e) {
      var_dump($e->getCode());
      return sendError($response, $e->getMessage());
    }
  }

  public function getACommande(Request $request, Response $response, array $args)
  {
    try {
      $order = $this->db->order->getAnOrder($args['id']);
      
      return sendJSON($response, $producer, 200);
    } catch (Exception $e) {
      var_dump($e->getCode());
      return sendError($response, $e->getMessage());
    }
  }

  // //Permet d'ajouter une commande
  // public function postCommande(RequestInterface $request, ResponseInterface $response, array $args)
  // {
  //   try {
  //     $id_orderWanted = $args['id'];
  //     $status_orderWanted = $args['status'];
  //     $date_orderWanted = $args['date'];
  //     $payement_orderWanted = $args['payement'];
  //     $id_producer_orderWanted = $args['id_producer'];
  //     $id_user_orderWanted = $args['id_user'];
  //     $order = $this->db->query("INSERT INTO commande VALUES ($id_orderWanted','$status_orderWanted','$date_orderWanted','$payement_orderWanted','$id_producer_orderWanted','$id_user_orderWanted';");
  //     $response->getBody()->write(json_encode($order));
  //     return $response;
  //   } catch (Exception $e) {
  //     return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
  //   }
  // }

  // //Permet de mettre à jour les informations d'une commande
  // public function putCommande(RequestInterface $request, ResponseInterface $response, array $args)
  // {
  //   try {
  //     $id_orderWanted = $args['id'];
  //     $status_orderWanted = $args['status'];
  //     $date_orderWanted = $args['date'];
  //     $payement_orderWanted = $args['payement'];
  //     $id_producer_orderWanted = $args['id_producer'];
  //     $id_user_orderWanted = $args['id_user'];
  //     $order = $this->db->query("UPDATE commande SET id_order='$id_orderWanted', status_order='$status_orderWanted', date_order='$date_orderWanted', payement_order='$payement_orderWanted', id_producer='$id_producer_orderWanted', id_user='$id_user_orderWanted';");
  //     $response->getBody()->write(json_encode($order));
  //     return $response;
  //   } catch (Exception $e) {
  //     return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
  //   }
  // }

  // //Permet de supprimer une commande
  // public function deleteCommande(RequestInterface $request, ResponseInterface $response, array $args)
  // {
  //   try {
  //     $id_orderWanted = $args['id'];
  //     $order = $this->db->query("DELETE FROM commande WHERE id_order='$id_orderWanted';");
  //     $response->getBody()->write(json_encode($order));
  //     return $response;
  //   } catch (Exception $e) {
  //     return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
  //   }
  // }
}