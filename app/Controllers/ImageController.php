<?php

namespace App\Controllers;

use App\Models\Controller;
use App\Models\Database;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use function App\Lib\sendJSON;
use function App\Lib\sendError;

class ImageController extends Controller
{
  public function __construct($db)
  {
    $this->db = $db;
  }

  function getImage(Request $request, Response $response, array $args){

    $file = dirname(dirname(__DIR__))  . "/ressource/image/". $args['name'];
    if (!file_exists($file)) {
        die("file:$file");
    }
    $image = file_get_contents($file);
    if ($image === false) {
        die("error getting image");
    }
    $type = pathinfo($file, PATHINFO_EXTENSION);
    $response->getBody()->write($image);
    return $response->withHeader('Content-Type', 'image/'.$type);
  }


}
?>