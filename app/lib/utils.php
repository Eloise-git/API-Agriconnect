<?php
namespace App\lib;

use Psr\Http\Message\ResponseInterface as Response;

function sendJSON(Response $response, $data, bool $error, int $status = 200)
{
  $res = [
    'error' => $error,
    'data' => $data
  ];
  $response->getBody()->write(json_encode($res));
  return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
}