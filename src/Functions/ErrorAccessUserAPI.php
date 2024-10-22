<?php

namespace App\Functions;

use \App\Functions\Date as AppDate;

class ErrorAccessUserAPI
{
    public static function getErrorAccess (array $errorAccess, \DateTime $currentDatetime): array
    {
        if (count($errorAccess) > 0) {
            # Données Response HTTP
            $response = [];
            $response['timestamp'] = $currentDatetime->format('Y-m-d H:i:s');
            $response['timezone'] = $currentDatetime->getTimezone()->getName();
            $response['status_code'] = $errorAccess['status_code'];
            $response['status'] = "errors";
            $response['error_code'] = $errorAccess['error_code'];
            $response['message'] = $errorAccess['message'];

            return $response;
        }

        return [];
    }
}

?>