<?php

use App\Middlewares\ErrorHandlingMiddleware;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->add(ErrorHandlingMiddleware::class);

// eloquent
require __DIR__ . '/../config/database.php';

// includes Routes files
require_once __DIR__ . '/../app/Routes/api.php';


$app->run();
