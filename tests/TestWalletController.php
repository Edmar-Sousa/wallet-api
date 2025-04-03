<?php declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Psr7\Factory\ServerRequestFactory;
use Tests\Fixtures\UserFixtures;
use Tests\Traits\BootApp;


class TestWalletController extends TestCase
{
    use BootApp;

    private App $app;


    public function setUp(): void
    {
        parent::setUp();

        $this->app = $this->setUpApp();
    }

    public function testCreateWalletMerchantInvalidData()
    {
        $request = (new ServerRequestFactory())->createServerRequest(
            'POST',
            '/wallet/user'
        );

        $request = $request
            ->withHeader('Content-Type', 'application/json')
            ->withBody(UserFixtures::createInvalidUser());

        $response = $this->app->handle($request);

        $responseBody =  json_decode((string) $response->getBody(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(400, $responseBody['status']);
        $this->assertEquals('validation_wallet_error', $responseBody['code']);

        $this->assertArrayHasKey('errors', $responseBody);

        $this->assertArrayHasKey('fullname', $responseBody['errors']);
        $this->assertArrayHasKey('cpfCnpj', $responseBody['errors']);
        $this->assertArrayHasKey('email', $responseBody['errors']);
    }


    public function testCreateWalletUserInvalidData()
    {
        $request = (new ServerRequestFactory())->createServerRequest(
            'POST',
            '/wallet/user'
        );

        $request = $request
            ->withHeader('Content-Type', 'application/json')
            ->withBody(UserFixtures::createInvalidUser());

        $response = $this->app->handle($request);

        $responseBody =  json_decode((string) $response->getBody(), true);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(400, $responseBody['status']);
        $this->assertEquals('validation_wallet_error', $responseBody['code']);

        $this->assertArrayHasKey('errors', $responseBody);

        $this->assertArrayHasKey('fullname', $responseBody['errors']);
        $this->assertArrayHasKey('cpfCnpj', $responseBody['errors']);
        $this->assertArrayHasKey('email', $responseBody['errors']);
    }


    public function testCreateUserWallet(): void
    {
        $request = (new ServerRequestFactory())->createServerRequest(
            'POST',
            '/wallet/user'
        );


        $request = $request
            ->withHeader('Content-Type', 'application/json')
            ->withBody(UserFixtures::createValidUser());

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
            ->withBody(UserFixtures::createValidUser(true));

        $response = $this->app->handle($request);

        $this->assertEquals(201, $response->getStatusCode());
    }
}