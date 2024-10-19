<?php

// src/Validator/NameConstraint.php
namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class NameConstraint extends Constraint
{
    public $message = 'Le nom ne peut contenir que des lettres, nombres, espaces, tirets et apostrophes.';


    public function validatedBy(): string
    {
        return NameConstraintValidator::class;
    }
}


?>