<?php

namespace App\Interfaces;

interface ValidatorInterface
{
    public function validate(array $data): void;
}
