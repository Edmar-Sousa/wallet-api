<?php declare(strict_types=1);

namespace Tests;

use App\Controllers\WalletController;

use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;
use Faker\Factory as Faker;

use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Stream;
use Tests\Traits\BootApp;
use Tests\Traits\hasFaker;


class TestWalletController extends TestCase
{
    use BootApp, hasFaker;

    private App $app;


    public function setUp(): void
    {
        parent::setUp();

        $this->faker = $this->setUpFaker();
        $this->app = $this->setUpApp();

        $this->app->post('/wallet/user', [WalletController::class, 'createWallet']);
    }


    public function testCreateUserWallet(): void
    {
        $request = (new ServerRequestFactory())->createServerRequest(
            'POST',
            '/wallet/user'
        );

        $requestBody = (new StreamFactory())->createStream(json_encode([
            'fullname' => $this->faker->name(),
            'cpfCnpj' => $this->faker->cpf(),
            'email' => $this->faker->email(),
            'password' => '123456',
        ]));


        $request = $request
            ->withHeader('Content-Type', 'application/json')
            ->withBody($requestBody);

        $response = $this->app->handle($request);

        $this->assertEquals(201, $response->getStatusCode());
    }
}