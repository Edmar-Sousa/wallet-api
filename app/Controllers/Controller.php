<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Exceptions\InternalErrorException;
use Illuminate\Database\Eloquent\Model;


class Controller
{

    /**
     * Function to create json to response 
     * 
     * @param array<string, string>|Model $data
     * 
     * @throws InternalErrorException
     * @return string
     */
    public final function json(array|Model $data): string
    {
        $json = json_encode($data);

        if ($json === false) {
            throw new InternalErrorException(
                'Error to parse json to return',
                [ 'message' => 'Erro ao montar json de resposta.' ]
            );
        }

        return $json;
    }
}
