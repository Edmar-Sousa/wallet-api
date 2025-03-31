<?php declare(strict_types=1);

namespace Tests\Traits;

use Slim\App;
use Slim\Factory\AppFactory;

trait BootApp
{
    public function setUpApp(): App
    {
        require_once __DIR__ . '/../../config/database.php';
        return AppFactory::create();
    }

}