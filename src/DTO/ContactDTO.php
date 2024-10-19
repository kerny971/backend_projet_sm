<?php

namespace App\DTO;

use App\Entity\User;

class ContactDTO
{
    private $firstName;
    private $lastName;
    private $email;
    private $message;

    public function __construct(User $user, string $message)
    {
        $this->firstName = $user->getFirstName();
        $this->lastName = $user->getLastName();
        $this->email = $user->getEmail();
        $this->message = $message;
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


    public function getMessage(): string
    {
        return $this->message;
    }
}


?>