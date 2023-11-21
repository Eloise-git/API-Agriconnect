<?php

use Slim\Factory\AppFactory;
use App\controllers;
use App\middlewares\AuthMiddleware;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->setBasePath("/api-agriconnect");

$app->addErrorMiddleware(true, true, true);

// Test route
$app->get('/', controllers\UserController::class . ':home');

// Auth routes
$app->post('/login', controllers\AuthController::class . ':login');
$app->post('/register', controllers\AuthController::class . ':register');

// Users routes
$app->get('/user', controllers\UserController::class . ':getUser')->add(AuthMiddleware::class);
$app->post('/user', controllers\UserController::class . ':getUser')->add(AuthMiddleware::class);
$app->put('/user', controllers\UserController::class . ':putUser')->add(AuthMiddleware::class);
$app->delete('/user', controllers\UserController::class . ':deleteUser')->add(AuthMiddleware::class);

// Products routes
$app->get('/products', controllers\ProductController::class . ':getAllProducts');
$app->get('/product', controllers\ProductController::class . ':getProduct');
$app->post('/product', controllers\ProductController::class . ':addProduct');
$app->put('/product', controllers\ProductController::class . ':putProduct');

// Messages routes
$app->get('/messages', controllers\MessagerieController::class . ':getAllMessages');

$app->run();

return $app;