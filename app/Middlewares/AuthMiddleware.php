<?php
namespace App\Middlewares;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use function App\Lib\sendError;

require_once dirname(__DIR__) . '/Lib/Utils.php';

class AuthMiddleware implements MiddlewareInterface
{
  public function process(Request $request, RequestHandler $handler): Response
  {
    try {
      $settings = dirname(__DIR__) . '/Settings/Settings.php';
      $key = $settings['settings']['jwt']['secret'];

      $auth = $request->getHeader('Authorization');
      if (!$auth) {
        throw new Exception("Vous n'êtes pas autorisé à accéder à cette ressource", 401);
      }

      $token = $auth[0];
      $token = explode(" ", $token)[1];
      $decoded = JWT::decode($token, new key($key, 'HS256'));

      $request = $request->withAttribute('user', $decoded->data);

      $response = $handler->handle($request);
      return $response;
    } catch (Exception $e) {
      $response = new \Slim\Psr7\Response();
      return sendError($response, "Vous n'êtes pas autorisé à accéder à cette ressource", 401);
    }
  }
}