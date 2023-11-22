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

  public function getAllStock(Request $request, Response $response, array $args)
  {
    try {
      $stock = $this->db->stock->getAllStock($args['id_producer']);

      $response->getBody()->write(json_encode($stock));
      return $response;
    } catch (Exception $e) {
      return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }

  public function putStock(Request $request, Response $response, array $args)
  {
    try {
      $stock = $this->db->stock->updateStockById($args['id_product'], $args['name_product'], $args['desc_product'], 
            $args['type_product'], $args['price_product'], $args['unit_product'], $args['stock_product'], $args['id_producer']);

      $response->getBody()->write(json_encode($stock));
      return $response;
    } catch (Exception $e) {
      return $response->withStatus(500)->getBody()->write(json_encode($e->getMessage()));
    }
  }
}