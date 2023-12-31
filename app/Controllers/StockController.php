<?php

namespace App\Controllers;

use App\Models\Controller;
use App\Models\Database;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use function App\Lib\sendJSON;
use function App\Lib\sendError;

require_once dirname(__DIR__) . '/Lib/Utils.php';

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
      $userId = $user->id;

      $producer = $this->db->producer->getProducerByUserId($userId);

      if (!$producer) {
        throw new Exception("Vous n'êtes pas un producteur", 400);
      }

      $id_producer = $producer['id_producer'];

      $stock = $this->db->stock->getAllStock($id_producer);

      return sendJSON($response, $stock, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }
  public function getAStock(Request $request, Response $response, array $args)
  {
    try {
      $user = $request->getAttribute('user');
      $userId = $user->id;
      $id_product = $args['id'] ?? null;

      if (!$id_product) {
        throw new Exception("Tous les champs sont obligatoires", 400);
      }

      $producer = $this->db->producer->getProducerByUserId($userId);

      if (!$producer) {
        throw new Exception("Vous n'êtes pas un producteur", 400);
      }

      $id_producer = $producer['id_producer'];

      $stock = $this->db->stock->getAStockById($id_product, $id_producer);

      return sendJSON($response, $stock, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }

  public function putStock(Request $request, Response $response, array $args)
  {
    try {
      $user = $request->getAttribute('user');
      $userId = $user->id;
      $id_product = $args['id'] ?? null;

      $rawdata = file_get_contents("php://input");
      parse_str($rawdata, $data);

      $stock_data = $data['stock'] ?? null;

      if (!$id_product || !$stock_data) {
        throw new Exception("Tous les champs sont obligatoires", 400);
      }

      $producer = $this->db->producer->getProducerByUserId($userId);

      if (!$producer) {
        throw new Exception("Vous n'êtes pas un producteur", 400);
      }

      $id_producer = $producer['id_producer'];

      $stock = $this->db->stock->updateStockById($id_product, $id_producer, $stock_data);

      return sendJSON($response, $stock, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }
}
