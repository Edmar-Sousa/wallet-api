<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\WalletType;
use App\Exceptions\InternalErrorException;
use App\Interfaces\UseCaseWalletInterface;
use App\Repositories\Wallet\WalletRepository;
use App\UseCases\UseCaseWallet;
use App\Validators\Wallet\ValidatorFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class WalletController extends Controller
{
    private UseCaseWalletInterface $useCaseWallet;


    public function __construct()
    {
        $this->useCaseWallet = new UseCaseWallet(new WalletRepository());
    }


    /**
     * Get data of wallet and return a json
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param array{'id': int} $args
     *
     * @throws InternalErrorException
     *
     * @return Response
     */
    public function findWallet(Request $request, Response $response, array $args): Response
    {
        $wallet = $this->useCaseWallet->findWallet(intval($args['id']));

        $response->getBody()
            ->write($this->json($wallet));

        return $response->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
    }

    /**
     * Create a new wallet of type user
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return Response
     */
    public function createWallet(Request $request, Response $response): Response
    {
        /**
         * @var array{
         *  "fullname": string,
         *  "cpfCnpj": string,
         *  "email": string,
         *  "password": string
         * }
         */
        $data = json_decode($request->getBody()->getContents(), true);

        $walletValidator = ValidatorFactory::create(WalletType::USER);
        $walletValidator->validate($data);

        $wallet = $this->useCaseWallet->createWallet($data, WalletType::USER);

        $response->getBody()
            ->write($this->json($wallet));

        return $response->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
    }



    /**
     * Create a wallet to merchant
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @throws InternalErrorException
     *
     * @return Response
     */
    public function createMerchantWallet(Request $request, Response $response): Response
    {
        /**
         * @var array{
         *  "fullname": string,
         *  "cpfCnpj": string,
         *  "email": string,
         *  "password": string
         * }
         */
        $data = json_decode($request->getBody()->getContents(), true);

        $walletValidator = ValidatorFactory::create(WalletType::MERCHANT);
        $walletValidator->validate($data);


        $wallet = $this->useCaseWallet->createWallet($data, WalletType::MERCHANT);

        $response->getBody()
            ->write($this->json($wallet));

        return $response->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
    }
}
