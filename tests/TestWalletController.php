<?php declare(strict_types=1);

namespace Tests;

use App\Controllers\WalletController;

use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;

use Tests\Traits\BootApp;


class TestWalletController extends TestCase
{
    use BootApp;

    private App $app;


    public function setUp(): void
    {
        parent::setUp();

        $this->app = $this->setUpApp();
        $this->app->get('/', WalletController::class);
    }


    public function testReturnSuccess(): void
    {
        $request = (new ServerRequestFactory())->createServerRequest('GET', '/');

        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
    }
}