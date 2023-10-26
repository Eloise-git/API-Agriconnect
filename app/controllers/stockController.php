<?php
namespace App\controllers;

use App\models\Database;
use Psr\Http\Stock\RequestInterface;
use Psr\Http\Stock\ResponseInterface;

class StockController
{
  private $container;

  private $db;

  public function __construct($container)
  {
    $this->container = $container;
    $this->db = new Database();
  }

  //Permet d'obtenir la liste des stocks
  public function getAllStock(RequestInterface $request, ResponseInterface $response, array $args)
  {
    try {
      $stock = $this->db->query('SELECT * FROM stock');
      $response->getBody()->write(json_encode($stock));
      return $response;
    } catch (Exception $e) {
      return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }

  //Permet de mettre à jour les informations d'un stock
  public function putAProduct(RequestInterface $request, ResponseInterface $response, array $args)
  {
    try {
      $id_productWanted = $args['id'];
      $name_productWanted = $args['name'];
      $desc_productWanted = $args['desc'];
      $type_productWanted = $args['type'];
      $price_productWanted = $args['price'];
      $unit_productWanted = $args['unit'];
      $stock_productWanted = $args['stock'];
      $id_producerWanted = $args['id_producer'];
      $stock = $this->db->query("UPDATE stock SET id_product ='$id_productWanted', name_product='$name_productWanted', desc_product='$desc_productWanted',type_product='$type_productWanted', price_product='$price_productWanted', stock_product='$stock_productWanted', id_producer ='$id_producerWanted',");
      $response->getBody()->write(json_encode($stock));
      return $response;
    } catch (Exception $e) {
      return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }
}