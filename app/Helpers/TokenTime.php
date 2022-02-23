<?php

namespace App\Helpers;

use Carbon\Carbon;

class TokenTime
{

    /**
     * Expired time setting
     *
     * @param strind $type
     * @return string
     */
    public static function for()
    {
        $now        = Carbon::now();
        $expired    = $now->addYear();
        
        return $expired->format('Y-m-d H:i:s');
    }
}
