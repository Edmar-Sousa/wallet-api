<?php declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\CustomException;
use App\UseCases\UseCaseTransfer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use RuntimeException;

class TransferController
{

    public function createTransfer(Request $request, Response $response): Response
    {
        $data = json_decode($request->getBody()->getContents(), true);
        $transferUseCase = new UseCaseTransfer();

        try {
            $transfer = $transferUseCase->transferBetweenWallets($data);

            // TODO: send notification

            $response->getBody()
                ->write(json_encode($transfer));

            return $response->withStatus(201)
                ->withHeader('Content-Type', 'application/json');
        }

        catch (CustomException $err)
        {
            $response->getBody()
                ->write(json_encode($err->getErrorObject()));

            return $response->withStatus($err->getCode())
                ->withHeader('Content-Type', 'application/json');
        }
    }

}