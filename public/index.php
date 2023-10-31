<?php

use Slim\Factory\AppFactory;
use App\controllers;
use App\middlewares\AuthMiddleware;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->setBasePath("/api-agriconnect");

$app->addErrorMiddleware(true, true, true);

// Test route
$app->get('/', controllers\UsersController::class . ':home');

// Auth routes
$app->post('/login', controllers\AuthController::class . ':login');
$app->post('/register', controllers\AuthController::class . ':register');

// Users routes
$app->get('/user', controllers\UsersController::class . ':getUser')->add(AuthMiddleware::class);

$app->get('/messages', controllers\MessagerieController::class . ':getAllMessages');

$app->run();
