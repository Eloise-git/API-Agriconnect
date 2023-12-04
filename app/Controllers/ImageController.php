<?php

namespace App\Controllers;

use App\Models\Controller;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use function App\Lib\sendJSON;
use function App\Lib\sendError;

require_once dirname(__DIR__) . '/Lib/Utils.php';

class ImageController extends Controller
{
  public function __construct()
  {
    $this->db = null;
  }

  function getImage(Request $request, Response $response, array $args)
  {
    try {
      $file = dirname(dirname(__DIR__))  . "/ressource/image/" . $args['name'];

      if (!file_exists($file)) {
        throw new Exception("Image pas trouvée", 404);
      }

      $image = file_get_contents($file);

      if ($image === false) {
        throw new Exception("Erreur lors de la lecture de l'image", 500);
      }

      $type = pathinfo($file, PATHINFO_EXTENSION);
      $response->getBody()->write($image);
      return $response->withHeader('Content-Type', 'image/' . $type);
    } catch (Exception $e) {
      return sendError($response, $e->getMessage());
    }
  }
}
