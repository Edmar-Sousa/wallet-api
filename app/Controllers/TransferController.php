<?php declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\Transfer\TransferRepository;
use App\Repositories\Wallet\WalletRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TransferController
{

    public function createTransfer(Request $request, Response $response): Response
    {
        $data = json_decode($request->getBody()->getContents(), true);

        $walletRepository = new WalletRepository();

        $walletPayer = $walletRepository->getWallet($data['payer']);
        $walletPayee = $walletRepository->getWallet($data['payee']);

        // TODO: check is wallets exists
        // TODO: check balance of payer wallet
        // TODO: check authorization

        $transferRepository = new TransferRepository();
        $transfer = $transferRepository->createTransfer([
            'payer' => $walletPayer,
            'payee' => $walletPayee,
            'value' => $data['value'],
        ]);

        // TODO: update balance
        // TODO: send notification

        $response->getBody()
            ->write(json_encode($transfer));

        return $response->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
    }

}