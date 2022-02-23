<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Error Language Lines
    |--------------------------------------------------------------------------
    |
    | Reference: https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
    |
    */

    /**
     * 2xx Success
     */
    '200' => [
        'code' => '200',
        'message' => 'OK',
    ],
    '201' => [
        'code' => '201',
        'message' => 'Created',
    ],

    /**
     * 4xx Client errors
     */
    '401' => [
        'code' => '401',
        'message' => 'Unauthorized',
    ],
    '403' => [
        'code' => '403',
        'message' => 'Forbidden',
    ],
    '404' => [
        'code' => '404',
        'message' => 'Not Found',
    ],
    '422' => [
        'code' => '422',
        'message' => 'Unprocessable Entity',
    ],

    /**
     * 5xx Server errors
     */
    '500' => [
        'code' => '500',
        'message' => 'Internal Server Error',
    ],
    '503' => [
        'code' => '503',
        'message' => 'Service Unavailable',
    ],

];
