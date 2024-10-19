<?php

namespace App\DTO;

use App\Entity\User;

class ConfirmationEmailDTO
{
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $code;

    public function __construct(User $user, string $code)
    {
        $this->firstName = $user->getFirstName();
        $this->lastName = $user->getLastName();
        $this->email = $user->getEmail();
        $this->code = $code;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }


    public function getCode(): string
    {
        return $this->code;
    }
}


?>