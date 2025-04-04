<?php

namespace Tests;

use App\Controllers\TransferController;
use App\Controllers\WalletController;
use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;
use Tests\Fixtures\TransferFixtures;
use Tests\Fixtures\UserFixtures;
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


    private function createUserWallet(bool $isMerchant = false)
    {
        $route = $isMerchant ? '/merchant' : '/user';

        $request = (new ServerRequestFactory())->createServerRequest(
            'POST',
            '/wallet' . $route,
        );

        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withBody(UserFixtures::createValidUser($isMerchant));

        return $this->app->handle($request);
    }

    public function testTransferBetweenUserAndMerchant()
    {
        $userPayer = json_decode($this->createUserWallet(true)->getBody());
        $userPayee = json_decode($this->createUserWallet()->getBody());

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

        $userPayer = $this->createUserWallet();
        $userPayer = json_decode($userPayer->getBody());

        $userPayee = $this->createUserWallet();
        $userPayee = json_decode($userPayee->getBody());


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