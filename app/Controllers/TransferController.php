<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\InternalErrorException;
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

    /**
     * Method to cancel transfer between two wallets
     * 
     * @throws InternalErrorException
     * 
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * 
     * @param array{'id': string} $args
     * @return Response
     */
    public function cancelTransfer(Request $request, Response $response, array $args): Response
    {
        $transferId = intval($args['id']);

        $this->useCaseTransfer->cancelTransfer($transferId);

        $json = json_encode([
            'message' => 'Transferencia cancelada com sucesso'
        ]);

        if ($json === false) {
            throw new InternalErrorException(
                'Error to parse json to return',
                [ 'message' => 'Erro ao montar json de resposta.' ]
            );
        }

        $response->getBody()
            ->write($json);

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
