<?php 

// src/Validator/SlugConstraint.php
namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class SlugConstraint extends Constraint
{
    public $message = 'Le slug "{{ value }}" n\'est pas valide. Il ne peut contenir que des lettres, nombre, tirets et underscores !';

    public function validatedBy(): string
    {
        return SlugConstraintValidator::class;
    }
}


?>