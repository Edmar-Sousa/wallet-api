<?php

declare(strict_types=1);

namespace App\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
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

    /**
     * Send a http request to check if transfer is authorized and return the result
     * 
     * @throws \RuntimeException
     * @return array{'status': string, 'data': array{'authorization': bool} }
     */
    private function fetchAuthorizationStatus(): array
    {
        try {
            $response = $this->client->get('/api/v2/authorize');


            /** @var array{status: string, data: array{authorization: bool}} $responseBody */
            $responseBody = json_decode($response->getBody()->getContents(), true);

            return [
                'status' => $responseBody['status'],
                'data' => $responseBody['data']
            ];
        } catch (ClientException $err) {

            if ($err->getResponse() && $err->getResponse()->getStatusCode() == 403) {
                return [
                    'status' => 'fail',
                    'data' => ['authorization' => false]
                ];
            }

            throw new RuntimeException('Client error');
        }
    }

    public function isAuthorized(): bool
    {
        $response = $this->fetchAuthorizationStatus();
        return $response['status'] === 'success' && $response['data']['authorization'] === true;
    }
}
