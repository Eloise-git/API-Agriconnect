<?php

use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;
use App\controllers;
use App\middlewares\AuthMiddleware;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->setBasePath("/api-agriconnect");

$app->addErrorMiddleware(true, true, true);

$app->options('/{routes:.+}', function ($request, $response, $args) {
  return $response;
});

$app->add(function ($request, $handler) {
  $response = $handler->handle($request);
  return $response
          ->withHeader('Access-Control-Allow-Origin', 'http://mysite')
          ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
          ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

// Test route
$app->get('/', controllers\UserController::class . ':home');

// Auth routes
$app->post('/login', controllers\AuthController::class . ':login');
$app->post('/register', controllers\AuthController::class . ':register');

// Users routes
$app->get('/user', controllers\UserController::class . ':getUser')->add(AuthMiddleware::class);
$app->get('/users', controllers\UserController::class . ':getAllUser')->add(AuthMiddleware::class);
$app->post('/user', controllers\UserController::class . ':getUser')->add(AuthMiddleware::class);
$app->put('/user', controllers\UserController::class . ':putUser')->add(AuthMiddleware::class);
$app->delete('/user/{id}', controllers\UserController::class . ':deleteUser')->add(AuthMiddleware::class);

//Producers routes
$app->get('/producers', controllers\ProducerController::class. ':getAllProducer');
$app->get('/producer/{id}', controllers\ProducerController::class. ':getProducerById');
$app->post('/producer', controllers\ProducerController::class. ':postProducer');
$app->put('/producer/{id}', controllers\ProducerController::class. ':updateProducerById');
$app->delete('/producer/{id}', controllers\ProducerController::class. ':deleteProducer');

// Products routes
$app->get('/products', controllers\ProductController::class . ':getAllProducts');
$app->get('/product', controllers\ProductController::class . ':getProduct');
$app->post('/product', controllers\ProductController::class . ':addProduct');
$app->put('/product', controllers\ProductController::class . ':putProduct');

//Orders routes
$app->get('/orders', controllers\CommandesController::class. 'getAllProducer');
$app->get('/order/{id}', controllers\CommandesController::class. 'getACommande');
$app->post('/order', controllers\CommandesController::class. 'postCommande');
$app->put('/order/{id}', controllers\CommandesController::class. 'putCommande');
$app->delete('/order/{id}', controllers\CommandesController::class. 'deleteCommande');

// Messages routes
$app->get('/messages', controllers\MessagerieController::class . ':getAllMessages');


// Last route
$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
  throw new HttpNotFoundException($request);
});
$app->run();

return $app;