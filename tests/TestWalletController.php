<?php declare(strict_types=1);

namespace Tests;

use App\Controllers\WalletController;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
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
        $this->app->post('/wallet/merchant', [WalletController::class, 'createMerchantWallet']);
    }


    private function createUser($isMerchant = false): StreamInterface
    {
        $stream = new StreamFactory();

        $cpfCnpj = $isMerchant ? $this->faker->cnpj() : $this->faker->cpf();

        return $stream->createStream(json_encode([
            'fullname' => $this->faker->name(),
            'cpfCnpj' => $cpfCnpj,
            'email' => $this->faker->email(),
            'password' => '123456',
        ]));

    }

    public function testCreateUserWallet(): void
    {
        $request = (new ServerRequestFactory())->createServerRequest(
            'POST',
            '/wallet/user'
        );


        $request = $request
            ->withHeader('Content-Type', 'application/json')
            ->withBody($this->createUser());

        $response = $this->app->handle($request);

        $this->assertEquals(201, $response->getStatusCode());
    }


    public function testCreateMerchantWallet(): void
    {
        $request = (new ServerRequestFactory())->createServerRequest(
            'POST',
            '/wallet/merchant'
        );


        $request = $request
            ->withHeader('Content-Type', 'application/json')
            ->withBody($this->createUser(true));

        $response = $this->app->handle($request);

        $this->assertEquals(201, $response->getStatusCode());
    }
}