<?php

namespace App\Interfaces;

/**
 * @template T of array
 */
interface ValidatorInterface
{

    /**
     * Apply rules to validate data
     * 
     * @param T $data
     * 
     * @return void
     */
    public function validate(array $data): void;
}
