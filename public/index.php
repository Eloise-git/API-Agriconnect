<?php

use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->setBasePath("/api-agriconnect");

$app->addErrorMiddleware(true, true, true);

$app->get('/', App\controllers\UsersController::class . ':home');

$app->post('/login', App\controllers\UsersController::class . ':login');

$app->post('/register', App\controllers\UsersController::class . ':register');



$app->run();
