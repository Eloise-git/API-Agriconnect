<?php
namespace App\controllers;

use App\models\Controller;
use App\models\Database;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use function App\lib\sendJSON;
use function App\lib\sendError;

require_once __DIR__ . '/../lib/utils.php';

class StockController extends Controller
{
  public function __construct()
  {
    $this->db = new Database();
  }

  public function getAllStock(Request $request, Response $response, array $args)
  {
    try {
      $user = $request->getAttribute('user');
      $userId=$user->id;
      
      $id_producer = $this->db->producer->getProducerByUserId($userId)[0]['id_producer'];
      var_dump($id_producer);
      $stock = $this->db->stock->getAllStock($id_producer);
      
      return sendJSON($response, $stock, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
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