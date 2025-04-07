<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Cache\CacheFactory;
use App\Enums\CacheType;
use App\Exceptions\InternalErrorException;
use App\Interfaces\UseCaseTransferInterface;
use App\Repositories\Transfer\TransferRepository;
use App\Repositories\Wallet\WalletRepository;
use App\UseCases\UseCaseTransfer;
use App\Validators\Transfer\TransferValidator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TransferController extends Controller
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

        $transfer = $this->useCaseTransfer->cancelTransfer($transferId);

        $cacheClient = CacheFactory::create(CacheType::REDIS);
        $cacheClient->enqueueMessageToNotifier('notifier_transfer', [ 'payee' => $transfer['payee'] ]);

        $response->getBody()
            ->write($this->json([
                'message' => 'Transferencia cancelada com sucesso'
            ]));

        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }


    /**
     * Method to create a transfer between two wallets
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return Response
     */
    public function createTransfer(Request $request, Response $response): Response
    {
        $validatorTransfer = new TransferValidator();

        /**
         * @var array{'payer': int, 'payee':int, 'value':float}
         */
        $data = json_decode($request->getBody()->getContents(), true);

        $validatorTransfer->validate($data);
        $transfer = $this->useCaseTransfer->transferBetweenWallets($data);

        $cacheClient = CacheFactory::create(CacheType::REDIS);
        $cacheClient->enqueueMessageToNotifier('notifier_transfer', [ 'payee' => $transfer['payee'] ]);


        $json = json_encode($transfer);

        if ($json === false) {
            throw new InternalErrorException(
                'Error to parse json to return',
                [ 'message' => 'Erro ao montar json de resposta.' ]
            );
        }

        $response->getBody()
            ->write($json);

        return $response->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
    }

}
