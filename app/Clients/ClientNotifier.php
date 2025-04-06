<?php

declare(strict_types=1);

namespace App\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use RuntimeException;

class ClientNotifier
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
     * Send notifier API
     * 
     * @throws \RuntimeException
     * @return array{'status': string, 'statusCode': int, 'message'?: string}
     */
    public function notifierPayeeUser(): array
    {
        try {
            $response = $this->client->post('/api/v1/notify');

            return [
                'status' => 'sucess',
                'statusCode' => $response->getStatusCode(),
            ];
        } catch (ServerException | ClientException $err) {

            if ($err->getResponse() && $err->getResponse()->getStatusCode() == 504) {

                /** @var array{'status': string, 'message': string} */
                $responseBody = json_decode($err->getResponse()->getBody()->getContents(), true);

                return [
                    'status'     => $responseBody['status'],
                    'statusCode' => $err->getResponse()->getStatusCode(),
                    'message'    => $responseBody['message'],
                ];
            }

            throw new RuntimeException($err->getMessage());
        }
    }

}
