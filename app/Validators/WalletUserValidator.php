<?php declare(strict_types=1);

namespace App\Validators;

use App\Interfaces\ValidatorInterface;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class WalletUserValidator implements ValidatorInterface
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

    public function validate(array $data): void
    {
        try {
            $validator = v::key('fullname', v::stringType()->notEmpty())
                ->key('email', v::email()->notEmpty())
                ->key('password', v::stringType()->notEmpty())
                ->key('cpfCnpj', v::cpf()->notEmpty());

            $validator->assert($data);
            $this->isValid = true;
        }

        catch (NestedValidationException $err)
        {
            $this->errorsMessage = $err->getMessages([
                'string' => '{{name}} deve ser uma texto',
                'empty' => '{{name}} não pode ser vazio',
                'email' => '{{name}} deve ser um email valido',
                'cpf' => '{{name}} deve ser um CPF valido',
            ]);

            $this->isValid = false;
        }

    }

}