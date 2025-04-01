<?php

namespace App\Validators;

use App\Interfaces\ValidatorInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class WalletMerchantValidator implements  ValidatorInterface
{

    protected bool $isValid = false;

    protected array $errorsMessage;


    public function isValid(): bool
    {
        return $this->isValid;
    }


    public function getErrorsMessage(): array
    {
        return $this->errorsMessage;
    }


    public function getErrorObject(): array
    {
        return [
            'status' => 400,
            'code' => 'validation_wallet_error',
            'errors' => $this->getErrorsMessage(),
        ];
    }

    public function validate(array $data): void
    {
        try {
            $validator = v::key('fullname', v::stringType()->notEmpty())
                ->key('email', v::email()->notEmpty())
                ->key('password', v::stringType()->notEmpty())
                ->key('cpfCnpj', v::cnpj()->notEmpty());

            $validator->assert($data);
            $this->isValid = true;
        }

        catch (NestedValidationException $err)
        {
            $this->errorsMessage = $err->getMessages([
                'string' => '{{name}} deve ser uma texto',
                'empty' => '{{name}} não pode ser vazio',
                'email' => '{{name}} deve ser um email valido',
                'cnpj' => '{{name}} deve ser um cnpj valido',
            ]);

            $this->isValid = false;
        }
    }
}