<?php
namespace App\middlewares;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use function App\lib\sendJSON;

require_once __DIR__ . '/../lib/utils.php';

class AuthMiddleware implements MiddlewareInterface
{
  public function process(Request $request, RequestHandler $handler): Response
  {
    try {
      $settings = require __DIR__ . '/../settings/settings.php';
      $key = $settings['settings']['jwt']['secret'];

      $token = $request->getHeader('Authorization')[0];
      $token = explode(" ", $token)[1];
      $decoded = JWT::decode($token, new key($key, 'HS256'));

      $request = $request->withAttribute('user', $decoded->data);

      $response = $handler->handle($request);
      return $response;
    } catch (Exception $e) {
      $response = new \Slim\Psr7\Response();
      // return sendJSON($response, "Vous n'êtes pas autorisé à accéder à cette ressource", 401);
      return sendJSON($response, $e->getMessage(), 401);
    }
  }
}