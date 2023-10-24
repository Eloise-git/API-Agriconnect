<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;
use services\connection\UserReaderRepository;



require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// Add Slim routing middleware
$app->addRoutingMiddleware();

// Set the base path to run the app in a subdirectory.
// This path is used in urlFor().
$app->add(new BasePathMiddleware($app));

$app->addErrorMiddleware(true, true, true);

// Define app routes
$app->get('/', function (Request $request, Response $response) {
  $response->getBody()->write('Hello, World!');
  return $response
    ->withHeader('Content-Type', 'application/json')
    ->withStatus(200);
})->setName('root');

$app->post("/auth/login", function (Request $request, Response $response) {
  $data = $request->getParsedBody();
  $email = $data['email'];
  $password = $data['password'];
  $UserReaderRepository = new UserReaderRepository($this->get('db'));
  $user = $UserReaderRepository->getUserByEmail($email);
  if (!$user) {
    return $response->withStatus(401);
  }


  $response->getBody()->write(json_encode($data));
  return $response
    ->withHeader('Content-Type', 'application/json')
    ->withStatus(200);
})->setName('login');
// Run app
$app->run();