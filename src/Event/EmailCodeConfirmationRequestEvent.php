<?php

namespace App\Event;

use App\DTO\ConfirmationEmailDTO;

class EmailCodeConfirmationRequestEvent
{

    public function __construct (
        private ConfirmationEmailDTO $confirmationEmailDTO
    ) {}

    public function getEmailConfirmationDTO (): ConfirmationEmailDTO
    {
        return $this->confirmationEmailDTO;
    }

}

?>