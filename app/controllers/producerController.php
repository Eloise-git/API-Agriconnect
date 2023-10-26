<?php
namespace App\controllers;

use App\models\Database;
use Psr\Http\Producer\RequestInterface;
use Psr\Http\Producer\ResponseInterface;

class ProducerController
{
  private $container;

  private $db;

  public function __construct($container)
  {
    $this->container = $container;
    $this->db = new Database();
  }

  //Permet d'obtenir la liste des producteurs
  public function getAllProducer(RequestInterface $request, ResponseInterface $response, array $args)
  {
    try {
        $producer = $this->db->query('SELECT * FROM producer');
        $response->getBody()->write(json_encode($producer));
        return $response;
    } catch (Exception $e) {
        return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }

  //Permet d'obtenir les informations d'un producteur
  public function getAProducer(RequestInterface $request, ResponseInterface $response, array $args)
  {
    try {
        $id_producerWanted = $args['id'];
        $producer = $this->db->query("SELECT * FROM producer WHERE id_producer ='$id_producerWanted");
        $response->getBody()->write(json_encode($producer));
        return $response;
    } catch (Exception $e) {
        return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }

  //Permet d'ajouter un producteur
  public function postProducer(RequestInterface $request, ResponseInterface $response, array $args)
  {
    try {
        $id_producerWanted = $args['id'];
        $desc_producerWanted = $args['desc'];
        $payement_producerWanted = $args['payement'];
        $name_producerWanted = $args['name'];
        $adress_producerWanted = $args['adress'];
        $phoneNumber_producerWanted = $args['phoneNumber'];
        $category_producerWanted = $args['category'];
        $id_userWanted = $args['id_user'];
        $producer = $this->db->query("INSERT INTO producer VALUES ($id_producerWanted','$desc_producerWanted','$payement_producerWanted','$adress_producerWanted','$phoneNumber_producerWanted','$category_producerWanted','$id_user_orderWanted';");
        $response->getBody()->write(json_encode($producer));
        return $response;
    }catch (Exception $e) {
        return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }

  //Permet de mettre à jour les informations d'un producteur
  public function putProducer(RequestInterface $request, ResponseInterface $response, array $args)
  {
    try {
        $id_producerWanted = $args['id'];
        $desc_producerWanted = $args['desc'];
        $payement_producerWanted = $args['payement'];
        $name_producerWanted = $args['name'];
        $adress_producerWanted = $args['adress'];
        $phoneNumber_producerWanted = $args['phoneNumber'];
        $category_producerWanted = $args['category'];
        $id_userWanted = $args['id_user'];
        $producer = $this->db->query("UPDATE producer SET id_producer='$id_producerWanted', desc_producer='$desc_producerWanted', payement_producer='$payement_orderWanted', name_producer='$name_producerWanted', adress_producer='$adress_producerWanted', phoneNumber_producer='$phoneNumber_producerWanted', category_producer='$category_producerWanted', id_user=' $id_userWanted';");
        $response->getBody()->write(json_encode($producer));
        return $response;
    } catch (Exception $e) {
        return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }

  //Permet de supprimer un producteur
  public function deleteProducer(RequestInterface $request, ResponseInterface $response, array $args)
  {
    try {
        $id_producerWanted = $args['id'];
        $producer = $this->db->query("DELETE FROM producer WHERE id_producer='$id_producerWanted';");
        $response->getBody()->write(json_encode($producer));
        return $response;
    } catch (Exception $e) {
        return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }
}