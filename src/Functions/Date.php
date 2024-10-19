<?php

namespace App\Functions;

use DateTime;
use DateTimeZone;

class Date {


    public static function current () {
        $timezone = $_ENV['APP_TIMEZONE'];
        return new DateTime('NOW', new DateTimeZone($timezone));
    }

}

?>