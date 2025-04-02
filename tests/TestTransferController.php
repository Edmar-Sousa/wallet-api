<?php

namespace Tests;

use App\Controllers\TransferController;
use App\Controllers\WalletController;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Response;
use Tests\Traits\BootApp;
use Tests\Traits\hasFaker;

class TestTransferController extends TestCase
{
    use BootApp, hasFaker;

    private App $app;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = $this->setUpFaker();
        $this->app = $this->setUpApp();

        $this->app->post('/transfer', [TransferController::class, 'createTransfer']);

        $this->app->post('/wallet/user', [WalletController::class, 'createWallet']);
        $this->app->post('/wallet/merchant', [WalletController::class, 'createMerchantWallet']);
    }


    private function createUser(): Response
    {
        $request = (new ServerRequestFactory())->createServerRequest(
            'POST',
            '/wallet/user'
        );

        $stream = (new StreamFactory())->createStream(json_encode([
            'fullname' => $this->faker->name(),
            'cpfCnpj' => $this->faker->cpf(),
            'email' => $this->faker->email(),
            'password' => '123456',
        ]));

        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withBody($stream);

        return $this->app->handle($request);
    }

    public function testTransferBetweenUserAndUser()
    {

        $userPayer = $this->createUser();
        $userPayer = json_decode($userPayer->getBody());

        $userPayee = $this->createUser();
        $userPayee = json_decode($userPayee->getBody());

        $stream = new StreamFactory();

        $transfer = $stream->createStream(json_encode([
            'value' => 10.50,
            'payer' => $userPayer->id,
            'payee' => $userPayee->id,
        ]));

        $request = (new ServerRequestFactory())->createServerRequest(
            'POST',
            '/transfer'
        );

        $request = $request->withHeader('Content-Type', 'application/json')
            ->withBody($transfer);

        $response = $this->app->handle($request);

        $this->assertEquals(201, $response->getStatusCode());
    }

}