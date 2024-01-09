<?php

use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use App\Controllers;
use App\Middlewares\AuthMiddleware;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$settings = require dirname(__DIR__) . '/app/Settings/Settings.php';
$basePath = $settings['settings']['app']['basePath'];

$app = AppFactory::create();

$app->setBasePath($basePath);

$app->addErrorMiddleware(true, true, true);

$app->addBodyParsingMiddleware();

$app->options('/{routes:.+}', function ($request, $response, $args) {
  return $response;
});

// This middleware will append the response header Access-Control-Allow-Methods with all allowed methods
$app->add(function (Request $request, RequestHandlerInterface $handler): Response {
  $routeContext = RouteContext::fromRequest($request);
  $routingResults = $routeContext->getRoutingResults();
  $methods = $routingResults->getAllowedMethods();
  $requestHeaders = $request->getHeaderLine('Access-Control-Request-Headers');

  $response = $handler->handle($request);

  $response = $response->withHeader('Access-Control-Allow-Origin', '*');
  $response = $response->withHeader('Access-Control-Allow-Methods', implode(',', $methods));
  $response = $response->withHeader('Access-Control-Allow-Headers', $requestHeaders);

  return $response;
});

$app->addRoutingMiddleware();

// Test route
$app->get('/', Controllers\UserController::class . ':home');

// Auth routes
$app->post('/login', Controllers\AuthController::class . ':login');
$app->post('/register', Controllers\AuthController::class . ':register');

// Users routes
$app->get('/user', Controllers\UserController::class . ':getCurrentUser')->add(AuthMiddleware::class);
$app->get('/user/{id}', Controllers\UserController::class . ':getUser')->add(AuthMiddleware::class);
$app->get('/users', Controllers\UserController::class . ':getAllUser')->add(AuthMiddleware::class);
$app->post('/user/accept', Controllers\UserController::class . ':changeAVisitorToClient')->add(AuthMiddleware::class);
$app->post('/user/refuse', Controllers\UserController::class . ':refuseUser')->add(AuthMiddleware::class);
$app->post('/user', Controllers\UserController::class . ':getUser')->add(AuthMiddleware::class);
$app->put('/user/{id}', Controllers\UserController::class . ':putUser')->add(AuthMiddleware::class);
$app->delete('/user/{id}', Controllers\UserController::class . ':deleteUser')->add(AuthMiddleware::class);

//Producers routes
$app->get('/producers', controllers\ProducerController::class . ':getAllProducer');
$app->get('/producer/search', controllers\ProducerController::class . ':searchByNameLocationTypeDistance');
$app->get('/producer/{id}', controllers\ProducerController::class . ':getProducerById');
$app->get('/producer', controllers\ProducerController::class . ':getProducerByName');
$app->post('/producer', controllers\ProducerController::class . ':postProducer');
$app->put('/producer/{id}', controllers\ProducerController::class . ':putProducer');
$app->delete('/producer/{id}', controllers\ProducerController::class . ':deleteProducer');

// Producer by user routes
$app->get('/producer/user/{id}', controllers\ProducerController::class . ':getProducerByUserId')->add(AuthMiddleware::class);

// Products routes
$app->get('/ressource/image/{name}', controllers\ImageController::class . ':getImage');

$app->get('/producer/{id}/products', Controllers\ProductController::class . ':getAllProducts');
$app->get('/product/{id}', Controllers\ProductController::class . ':getProduct');
$app->post('/product', Controllers\ProductController::class . ':addProduct')->add(AuthMiddleware::class);
$app->put('/product/{id}', Controllers\ProductController::class . ':updateProduct');
$app->delete('/product/{id}', Controllers\ProductController::class . ':deleteProduct');

//Orders routes
$app->get('/orders', Controllers\CommandesController::class . ':getAllCommandes')->add(AuthMiddleware::class);
$app->get('/order/{id}', Controllers\CommandesController::class . ':getACommande')->add(AuthMiddleware::class);
$app->post('/order', Controllers\CommandesController::class . ':postCommande')->add(AuthMiddleware::class);
$app->patch('/order/{id}', Controllers\CommandesController::class . ':pathCommande')->add(AuthMiddleware::class);
$app->delete('/order/{id}', Controllers\CommandesController::class . ':deleteCommande')->add(AuthMiddleware::class);

// Messages routes
$app->get('/messages', Controllers\MessagerieController::class . ':getAllMessages')->add(AuthMiddleware::class);
$app->get('/message/{id}', Controllers\MessagerieController::class . ':getAMessage')->add(AuthMiddleware::class);
$app->get('/messages/{id}', Controllers\MessagerieController::class . ':getAMessageByUserId')->add(AuthMiddleware::class);
$app->post('/message', Controllers\MessagerieController::class . ':postMessage')->add(AuthMiddleware::class);
$app->delete('/message/{id}', Controllers\MessagerieController::class . ':deleteMessage');

//Stocks routes
$app->get('/stocks', Controllers\StockController::class . ':getAllStock')->add(AuthMiddleware::class);
$app->get('/stock/{id}', Controllers\StockController::class . ':getAStock')->add(AuthMiddleware::class);
$app->put('/stock/{id}', Controllers\StockController::class . ':putStock')->add(AuthMiddleware::class);

// Last route
$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
  throw new HttpNotFoundException($request);
});
$app->run();

return $app;
