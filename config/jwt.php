<?php

return [

    /*
    |--------------------------------------------------------------------------
    | JWT KEY
    |--------------------------------------------------------------------------
    |
    | This key is used by jwt decode and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Issuer
    |--------------------------------------------------------------------------
    |
    | Identifier (or, name) of the server or system issuing the token.
    | Typically a DNS name, but doesn't have to be.
    |
    */

    'iss' => env('JWT_ISS', 'ASLIRI'),

    /*
    |--------------------------------------------------------------------------
    | Audience
    |--------------------------------------------------------------------------
    |
    | Intended recipient of this token;
    | can be any string, as long as the other end uses the same string when validating the token.
    | Typically a DNS name.
    |
    */

    'aud' => env('JWT_AUD', 'ASLIRI'),

];
