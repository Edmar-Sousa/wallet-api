<?php

namespace Tests;

use App\Enums\WalletType;
use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;
use Tests\Fixtures\TransferFixtures;
use Tests\TestsFactory\WalletFactory;
use Tests\Traits\BootApp;

class TestTransferController extends TestCase
{
    use BootApp;

    private App $app;

    public function setUp(): void
    {
        parent::setUp();
        $this->app = $this->setUpApp();
    }

    public function testTryTransferInsufficientBalance()
    {
        $userPayer = WalletFactory::createWalletInDatabaseWithoutBalance();
        $userPayee = WalletFactory::createWalletInDatabaseWithoutBalance();

        $request = (new ServerRequestFactory())->createServerRequest(
            'POST',
            '/transfer'
        );

        $request = $request->withHeader('Content-Type', 'application/json')
            ->withBody(TransferFixtures::createValidTransfer(
                $userPayer->id,
                $userPayee->id,
                10.50
            ));

        $response = $this->app->handle($request);

        $this->assertEquals(422, $response->getStatusCode());
    }

    public function testTransferBetweenUserAndMerchant()
    {
        $userPayer = WalletFactory::createWalletInDatabaseWithoutBalance(WalletType::MERCHANT);
        $userPayee = WalletFactory::createWalletInDatabaseWithoutBalance();

        $request = (new ServerRequestFactory())->createServerRequest(
            'POST',
            '/transfer'
        );

        $request = $request->withHeader('Content-Type', 'application/json')
            ->withBody(TransferFixtures::createValidTransfer(
                $userPayer->id,
                $userPayee->id,
                10.50
            ));

        $response = $this->app->handle($request);

        $this->assertEquals(422, $response->getStatusCode());
    }

    public function testTransferBetweenUserAndUser()
    {
        $userPayer = WalletFactory::createWalletInDatabaseWithoutBalance(WalletType::USER, 100 * 100); // R$ 100 in cent
        $userPayee = WalletFactory::createWalletInDatabaseWithoutBalance();

        $request = (new ServerRequestFactory())->createServerRequest(
            'POST',
            '/transfer'
        );

        $request = $request->withHeader('Content-Type', 'application/json')
            ->withBody(TransferFixtures::createValidTransfer(
                $userPayer->id,
                $userPayee->id,
                10.50
            ));

        $response = $this->app->handle($request);

        $this->assertEquals(201, $response->getStatusCode());
    }

}