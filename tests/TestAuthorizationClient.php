<?php

namespace Tests;

use App\Clients\ClientAuthorization;
use PHPUnit\Framework\TestCase;

class TestAuthorizationClient extends TestCase
{

    public function testAuthorizationRequest(): void
    {
        $client = new ClientAuthorization();

        $responseBody = $client->isAuthorized();
        $this->assertIsBool($responseBody);
    }

}