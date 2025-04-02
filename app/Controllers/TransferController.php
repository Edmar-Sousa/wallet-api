<?php declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\Transfer\TransferRepository;
use App\Repositories\Wallet\WalletRepository;
use App\UseCases\UseCaseTransfer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TransferController
{

    public function createTransfer(Request $request, Response $response): Response
    {
        $data = json_decode($request->getBody()->getContents(), true);
        $transferUseCase = new UseCaseTransfer();

        $transfer = $transferUseCase->transferBetweenWallets($data);

        // TODO: send notification

        $response->getBody()
            ->write(json_encode($transfer));

        return $response->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
    }

}