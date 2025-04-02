<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Exceptions\CustomException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use Slim\Psr7\Response as SlimResponse;
use Exception;


class ErrorHandlingMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        try {
            $response = $handler->handle($request);
            $response->withAddedHeader('Content-Type', 'application/json');

            return $response;
        } catch (CustomException $err) {
            $response = (new SlimResponse())->withStatus($err->getCode())
                ->withAddedHeader('Content-Type', 'application/json');

            $response->getBody()
                ->write(json_encode($err->getErrorObject()));

            return $response;
        } catch (Exception $err) {
            $response = (new SlimResponse())
                ->withStatus(500)
                ->withAddedHeader('Content-Type', 'application/json');

            $response->getBody()
                ->write(json_encode([
                    'status' => 500,
                    'code' => 'internal_error',
                    'errors' => [
                        'message' => 'Erro interno no servidor'
                    ]
                ]));

            return $response;
        }
    }
}
