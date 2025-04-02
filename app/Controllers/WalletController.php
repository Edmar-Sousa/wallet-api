<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\WalletType;
use App\Exceptions\CustomException;
use App\UseCases\UseCaseWallet;
use App\Validators\Wallet\ValidatorFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class WalletController
{
    public function findWallet(Request $request, Response $response, array $args): Response
    {
        try {
            $walletUserCase = new UseCaseWallet();
            $wallet = $walletUserCase->findWallet($args['id']);

            $response->getBody()
                ->write(json_encode($wallet));

            return $response->withStatus(201)
                ->withHeader('Content-Type', 'application/json');
        } catch (CustomException $err) {
            $response->getBody()
                ->write(json_encode($err->getErrorObject()));

            return $response->withStatus($err->getCode())
                ->withHeader('Content-Type', 'application/json');
        }
    }

    public function createWallet(Request $request, Response $response): Response
    {
        $walletValidator = ValidatorFactory::create(WalletType::USER);
        $data = json_decode($request->getBody()->getContents(), true);

        try {
            $walletValidator->validate($data);
            $walletUserCase = new UseCaseWallet();

            $wallet = $walletUserCase->createWallet($data, WalletType::USER);

            $response->getBody()
                ->write(json_encode($wallet));

            return $response->withStatus(201)
                ->withHeader('Content-Type', 'application/json');

        } catch (CustomException $err) {
            $response->getBody()
                ->write(json_encode($err->getErrorObject()));

            return $response->withStatus($err->getCode())
                ->withHeader('Content-Type', 'application/json');
        }
    }



    public function createMerchantWallet(Request $request, Response $response): Response
    {
        $walletValidator = ValidatorFactory::create(WalletType::MERCHANT);
        $data = json_decode($request->getBody()->getContents(), true);

        try {
            $walletValidator->validate($data);
            $walletUserCase = new UseCaseWallet();

            $wallet = $walletUserCase->createWallet($data, WalletType::MERCHANT);

            $response->getBody()
                ->write(json_encode($wallet));

            return $response->withStatus(201)
                ->withHeader('Content-Type', 'application/json');

        } catch (CustomException $err) {
            $response->getBody()
                ->write(json_encode($err->getErrorObject()));

            return $response->withStatus($err->getCode())
                ->withHeader('Content-Type', 'application/json');
        }
    }
}
