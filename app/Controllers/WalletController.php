<?php declare(strict_types=1);

namespace App\Controllers;

use App\Models\Wallet;
use App\Repositories\Wallet\WalletRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class WalletController
{

    public function createWallet(Request $request, Response $response): Response
    {
        $data = json_decode($request->getBody()->getContents(), true);

        $walletRepository = new WalletRepository();
        $wallet = $walletRepository->createUserWallet($data);

        $response->getBody()
            ->write(json_encode($wallet));

        return $response->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
    }

}