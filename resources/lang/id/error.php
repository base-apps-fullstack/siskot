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
        'message' => 'Disimpan',
    ],

    /**
     * 4xx Client errors
     */
    '401' => [
        'code' => '401',
        'message' => 'Tidak ada otorisasi',
    ],
    '403' => [
        'code' => '403',
        'message' => 'Akses dilarang',
    ],
    '404' => [
        'code' => '404',
        'message' => 'Tidak ditemukan',
    ],
    '422' => [
        'code' => '422',
        'message' => 'Tidak bisa diproses. Harap cek lagi.',
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
