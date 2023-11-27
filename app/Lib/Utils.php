<?php
namespace App\Lib;

use Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;

function sendJSON(Response $response, $data, int $status = 200)
{
  $res = [
    'error' => false,
    'data' => $data
  ];
  $response->getBody()->write(json_encode($res));
  return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
}

function sendError(Response $response, $message, int $status = 500)
{
  $res = [
    'error' => true,
    'message' => $message
  ];
  $response->getBody()->write(json_encode($res));
  return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
}

function generateToken($userId, $userRole)
{
  $settings = require dirname(__DIR__) . '/Settings/Settings.php';
      $key = $settings['settings']['jwt']['secret'];
      $payload = array(
        "iat" => time(),
        "exp" => time() + 30 * 24 * 60 * 60, // 30 jours
        "data" => [
          "id" => $userId,
          "role" => $userRole
        ]
      );

      $jwt = JWT::encode($payload, $key, 'HS256');
      return $jwt;
}
function hashPassword($password)
{
  return hash('sha256', $password);
}