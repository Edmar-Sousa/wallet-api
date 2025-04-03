<?php declare(strict_types=1);

namespace Tests\Traits;

use App\Middlewares\ErrorHandlingMiddleware;
use Slim\App;
use Slim\Factory\AppFactory;

trait BootApp
{
    public function setUpApp(): App
    {
        require_once __DIR__ . '/../../config/database.php';

        $app = AppFactory::create();

        require __DIR__ . '/../../app/Routes/api.php';

        $app->add(ErrorHandlingMiddleware::class);

        return $app;
    }

}