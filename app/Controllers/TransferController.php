<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\CustomException;
use App\Interfaces\UseCaseTransferInterface;
use App\Repositories\Transfer\TransferRepository;
use App\Repositories\Wallet\WalletRepository;
use App\UseCases\UseCaseTransfer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TransferController
{

    private UseCaseTransferInterface $useCaseTransfer;

    public function __construct()
    {
        $this->useCaseTransfer = new UseCaseTransfer(
            new WalletRepository(),
            new TransferRepository(),
        );
    }

    public function cancelTransfer(Request $request, Response $response, array $args)
    {
        $transferId = intval($args['id']);

        $this->useCaseTransfer->cancelTransfer($transferId);

        $response->getBody()
            ->write(json_encode([
                'message' => 'Transferencia cancelada com sucesso'
            ]));

        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }


    public function createTransfer(Request $request, Response $response): Response
    {
        $data = json_decode($request->getBody()->getContents(), true);


        $transfer = $this->useCaseTransfer->transferBetweenWallets($data);

        // TODO: send notification

        $response->getBody()
            ->write(json_encode($transfer));

        return $response->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
    }

}
