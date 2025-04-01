<?php declare(strict_types=1);

namespace Tests\Traits;

use Faker\Factory as Faker;
use Faker\Generator as Generator;

trait hasFaker
{

    public function setUpFaker(): Generator
    {
        return Faker::create('pt_BR');
    }

}