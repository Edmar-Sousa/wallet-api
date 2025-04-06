<?php

namespace App\Interfaces;

interface ValidatorInterface
{
    /**
     * Apply rules to validate data to store wallet
     * 
     * @param array{
     *  "fullname": string, 
     *  "cpfCnpj": string, 
     *  "email": string, 
     *  "password": string 
     * } $data
     * 
     * @return void
     */
    public function validate(array $data): void;
}
