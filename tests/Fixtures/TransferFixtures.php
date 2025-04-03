<?php

namespace Tests\Fixtures;

use Slim\Psr7\Factory\StreamFactory;

class TransferFixtures
{

    public static function createValidTransfer(string $payer, string $payee, float $amount)
    {
        $stream = new StreamFactory();

        return $stream->createStream(json_encode([
            'value' => $amount,
            'payer' => $payer,
            'payee' => $payee,
        ]));

    }

}