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

class ProductController extends Controller
{
  public function __construct()
  {
    $this->db = new Database();
  }

  //`GET /api/products` : Permet d'obtenir la liste des produits
  public function getAllProducts(Request $request, Response $response, array $args)
  {
    try {
      $products = $this->db->product->getAll();
      return sendJSON($response, $products, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }


  public function getProduct(Request $request, Response $response,array $args)
  {
    try {
      $userId = $args['id'];
      $product = $this->db->product->getProductById($userId);

      return sendJSON($response, $product, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }


  public function addProduct(Request $request, Response $response,array $args)
  {
    try {
      
      $user = $request->getAttribute('user');
      $userId = $user->id;
      $data = $request->getParsedBody();
      
      $id = uniqid();
      $name = $data['name'] ?? null;
      $description = $data['description'] ?? null;
      $type = $data['type'] ?? null;
      $price = $data['price'] ?? null;
      $unit = $data['unit'] ?? null;
      $stock = $data['stock'] ?? null;
      $image = $data['image'] ?? null;
      $id_producer = $this->db->producer->getProducerByUserId($userId)[0]['id_producer'];

      if (!$name || !$description || !$type || !$price || !$unit || !$stock || !$image ) {
        throw new Exception("Tous les champs sont obligatoires", 400);
      }

      $product = $this->db->product->addProduct($id,$name, $description, $type, $price, $unit, $stock,$image, $id_producer);

      return sendJSON($response, $product, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }


  public function updateProduct(Request $request, Response $response,array $args)
  {
    try {
      $productId = $args['id'];
      $rawdata = file_get_contents("php://input");
      parse_str($rawdata,$data);
      
      $name = $data['name'] ?? null;
      $description = $data['description'] ?? null;
      $type = $data['type'] ?? null;
      $price = $data['price'] ?? null;
      $unit = $data['unit'] ?? null;
      $stock = $data['stock'] ?? null;
      $image = $data['image'] ?? null;
      
      if (!$name || !$description || !$type || !$price || !$unit || !$stock) {
        throw new Exception("Tous les champs sont obligatoires", 400);
      }

      $product = $this->db->product->updateProductById($productId, $name, $description, $type, $price, $unit, $stock, $image);

      return sendJSON($response, $product, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }
  public function deleteProduct(Request $request, Response $response,array $args)
  {
    try {
      $productId = $args['id'];
      $this->db->product->deleteProductById($productId);

      return sendJSON($response, "Le produit a bien été supprimé", 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }
}