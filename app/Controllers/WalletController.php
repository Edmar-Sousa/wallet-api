<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\WalletType;
use App\Repositories\Wallet\WalletRepository;
use App\Validators\ValidatorFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class WalletController
{
    public function createWallet(Request $request, Response $response): Response
    {
        $walletValidator = ValidatorFactory::create(WalletType::USER);
        $data = json_decode($request->getBody()->getContents(), true);

        $walletValidator->validate($data);

        if (!$walletValidator->isValid()) {
            $response->getBody()
                ->write(json_encode($walletValidator->getErrorObject()));

            return $response->withStatus(400);
        }

        $walletRepository = new WalletRepository();
        $wallet = $walletRepository->createUserWallet($data);

        $response->getBody()
            ->write(json_encode($wallet));

        return $response->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
    }



    public function createMerchantWallet(Request $request, Response $response): Response
    {
        $walletValidator = ValidatorFactory::create(WalletType::MERCHANT);
        $data = json_decode($request->getBody()->getContents(), true);

        $walletValidator->validate($data);

        if (!$walletValidator->isValid()) {
            $response->getBody()
                ->write(json_encode($walletValidator->getErrorObject()));

            return $response->withStatus(400);
        }

        $walletRepository = new WalletRepository();
        $wallet = $walletRepository->createMerchantWallet($data);

        $response->getBody()
            ->write(json_encode($wallet));

        return $response->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
    }
}
