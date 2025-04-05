<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\WalletType;
use App\Interfaces\UseCaseWalletInterface;
use App\Repositories\Wallet\WalletRepository;
use App\UseCases\UseCaseWallet;
use App\Validators\Wallet\ValidatorFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class WalletController
{
    private UseCaseWalletInterface $useCaseWallet;


    public function __construct()
    {
        $this->useCaseWallet = new UseCaseWallet(new WalletRepository());
    }


    public function findWallet(Request $request, Response $response, array $args): Response
    {
        $wallet = $this->useCaseWallet->findWallet($args['id']);

        $response->getBody()
            ->write(json_encode($wallet));

        return $response->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
    }

    public function createWallet(Request $request, Response $response): Response
    {
        $walletValidator = ValidatorFactory::create(WalletType::USER);
        $data = json_decode($request->getBody()->getContents(), true);

        $walletValidator->validate($data);
        $wallet = $this->useCaseWallet->createWallet($data, WalletType::USER);

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
        $walletUserCase = new UseCaseWallet();

        $wallet = $walletUserCase->createWallet($data, WalletType::MERCHANT);

        $response->getBody()
            ->write(json_encode($wallet));

        return $response->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
    }
}
