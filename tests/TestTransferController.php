<?php

namespace Tests;

use App\Enums\WalletType;
use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;
use Tests\Fixtures\TransferFixtures;
use Tests\TestsFactory\TransferFactory;
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

    public function testCancelTransferWithoutBalance()
    {
        $balanceInCents = 10.50 * 100; // R$ 10.50 in cents

        $userPayer = WalletFactory::createWalletInDatabaseWithoutBalance();
        $userPayee = WalletFactory::createWalletInDatabaseWithoutBalance();

        $transfer = TransferFactory::createTransfer(
            $userPayer->id,
            $userPayee->id,
            $balanceInCents
        );

        $request = (new ServerRequestFactory())->createServerRequest(
            'DELETE',
            "/transfers/{$transfer->id}/cancellation"
        );

        $response = $this->app->handle($request);
        $this->assertEquals(422, $response->getStatusCode());
    }

    public function testCancelTransfer()
    {
        $balanceInCents = 10.50 * 100; // R$ 10.50 in cents

        $userPayer = WalletFactory::createWalletInDatabaseWithoutBalance();
        $userPayee = WalletFactory::createWalletInDatabaseWithoutBalance(balance: $balanceInCents);

        $transfer = TransferFactory::createTransfer(
            $userPayer->id,
            $userPayee->id,
            $balanceInCents
        );

        $request = (new ServerRequestFactory())->createServerRequest(
            'DELETE',
            "/transfers/{$transfer->id}/cancellation"
        );

        $response = $this->app->handle($request);
        $this->assertEquals(200, $response->getStatusCode());
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