<?php

namespace App\Controllers;

use App\Models\Controller;
use App\Models\Database;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use function App\Lib\sendJSON;
use function App\Lib\sendError;
use function App\Lib\verificationImage;
use function App\Lib\uploadImage;

require_once dirname(__DIR__) . '/Lib/ImageVerif.php';
require_once dirname(__DIR__) . '/Lib/Utils.php';

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
      $id_producer = $args['id'] ?? null;

      if (!$id_producer) {
        throw new Exception("Tous les champs sont obligatoires", 400);
      }

      $products = $this->db->product->getAllbyidproducer($id_producer);

      return sendJSON($response, $products, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }


  public function getProduct(Request $request, Response $response, array $args)
  {
    try {
      $userId = $args['id'] ?? null;

      if (!$userId) {
        throw new Exception("Tous les champs sont obligatoires", 400);
      }

      $product = $this->db->product->getProductById($userId);

      return sendJSON($response, $product, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }


  public function addProduct(Request $request, Response $response, array $args)
  {
    try {
      $user = $request->getAttribute('user');
      $userId = $user->id;

      $data = $request->getParsedBody();

      $name = $data['name'] ?? null;
      $description = $data['description'] ?? null;
      $type = $data['type'] ?? null;
      $price = $data['price'] ?? null;
      $unit = $data['unit'] ?? null;
      $stock = $data['stock'] ?? null;
      $image = $request->getUploadedFiles()['image'] ?? null;

      if (!$name || !$description || !$type || !$price || !$unit || !$stock || !$image) {
        throw new Exception("Tous les champs sont obligatoires", 400);
      }

      $id = uniqid();
      $id_producer = $this->db->producer->getProducerByUserId($userId)['id_producer'];

      if (!$id_producer) {
        throw new Exception("Vous n'avez pas de producteur", 400);
      }

      $directory = dirname(dirname(__DIR__)) . '/ressource/image';

      verificationImage($image);
      $imageName = uploadImage($image, $directory);

      $product = $this->db->product->addProduct($id, $name, $description, $type, $price, $unit, $stock, $imageName, $id_producer);

      return sendJSON($response, $product, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }



  public function updateProduct(Request $request, Response $response, array $args)
  {
    try {
      $productId = $args['id'] ?? null;
      $rawdata = file_get_contents("php://input");
      parse_str($rawdata, $data);

      $name = $data['name'] ?? null;
      $type = $data['type'] ?? null;
      $price = $data['price'] ?? null;
      $unit = $data['unit'] ?? null;
      $stock = $data['stock'] ?? null;

      if (!$productId || !$name || !$type || !$price || !$unit || !$stock) {
        throw new Exception("Tous les champs sont obligatoires", 400);
      }

      $product = $this->db->product->updateProductById($productId, $name, $type, $price, $unit, $stock);
      return sendJSON($response, $product, 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }
  public function deleteProduct(Request $request, Response $response, array $args)
  {
    try {
      $productId = $args['id'] ?? null;

      if (!$productId) {
        throw new Exception("Tous les champs sont obligatoires", 400);
      }

      $this->db->product->deleteProductById($productId);

      return sendJSON($response, "Le produit a bien été supprimé", 200);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }
}
