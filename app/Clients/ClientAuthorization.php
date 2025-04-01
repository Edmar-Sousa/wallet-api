<?php declare(strict_types=1);

namespace App\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Slim\Exception\HttpForbiddenException;

use RuntimeException;

class ClientAuthorization
{

    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://util.devi.tools',
            'timeout'  => 2.0,
        ]);
    }

    private function fetchAuthorizationStatus(): array
    {
        try {
            $response = $this->client->get('/api/v2/authorize');

            $responseBody = json_decode($response->getBody()->getContents(), true);

            if (!isset($responseBody['status'], $responseBody['data']['authorization']))
                throw new RuntimeException('Unexpected error');

            return $responseBody;
        }

        catch (ClientException $err) {

            if ($err->getResponse() && $err->getResponse()->getStatusCode() == 403)
                return [
                    'status' => 'fail',
                    'data' => ['authorization' => false]
                ];

            throw new RuntimeException('Client error');
        }
    }

    public function isAuthorized(): bool
    {
        $response = $this->fetchAuthorizationStatus();
        return $response['status'] === 'success' && $response['data']['authorization'] == true;
    }
}