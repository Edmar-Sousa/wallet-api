<?php

namespace App\Interfaces;

interface ValidatorInterface
{
    public function isValid(): bool;
    public function getErrorsMessage(): array;
    public function validate(array $data): void;
    public function getErrorObject(): array;

}