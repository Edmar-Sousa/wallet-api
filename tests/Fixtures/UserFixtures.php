<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Faker\Factory as Faker;
use Slim\Psr7\Factory\StreamFactory;

class UserFixtures
{

    public static function createValidUser($isMerchant = false)
    {
        $stream = new StreamFactory();
        $faker = Faker::create('pt_BR');

        $cpfCnpj = $isMerchant ? $faker->cnpj() : $faker->cpf();

        return $stream->createStream(json_encode([
            'fullname' => $faker->name(),
            'cpfCnpj' => $cpfCnpj,
            'email' => $faker->email(),
            'password' => '123456',
        ]));
    }

    public static function createInvalidUser()
    {
        $stream = new StreamFactory();
        $faker = Faker::create('pt_BR');

        return $stream->createStream(json_encode([
            'fullname' => '',
            'cpfCnpj' => '92.444.627/0011-30',
            'email' => 'teste',
            'password' => '123456',
        ]));
    }
}