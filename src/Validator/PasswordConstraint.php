<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class PasswordConstraint extends Constraint
{
    public $message = 'Le mot de passe doit contenir au moins 8 caractères dont une lettre majuscule, une lettre minuscule, un chiffre et un caractère spéciale';

    public function validatedBy(): string
    {
        return PasswordConstraintValidator::class;
    }
}

?>