<?php

namespace App\Traits\Service;

/**
 * Request Handler
 */
trait RequestHandler
{
    
    /**
     * Get mixed value to be an array
     *
     * @param mixed $mixedValue
     * @return array|boolean
     */
    public function getMixedValue($mixedValue)
    {
        if ($mixedValue) :
            switch (getType($mixedValue)) :
                case 'string':
                    $value = json_decode($mixedValue);
                    break;
                default:
                    $value = $mixedValue;
                    break;
            endswitch;

            return array_wrap($value);
        endif;

        return false;
    }
}
