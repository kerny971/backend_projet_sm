<?php

namespace App\Message;

use App\Entity\User;

final readonly class EmailCodeConfirmationMessage
{
    /*
     * Add whatever properties and methods you need
     * to hold the data for this message class.
     */

     public function __construct(
         private string $_userId,
     ) {}

     public function getUserId (): string
     {
         return $this->_userId;
     }
}
