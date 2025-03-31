<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// eloquent
require __DIR__ . '/../config/database.php';

// includes routes files
require_once __DIR__ . '/../src/routes/api.php';


$app->run();
