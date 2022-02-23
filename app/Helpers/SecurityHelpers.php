<?php

if (! function_exists('appencrypt')) {
    function appencrypt($value)
    {
        $key   = hash('sha256', config('app.key'));
        $iv    = substr($key, 0, openssl_cipher_iv_length(config('app.cipher')));

        $value = serialize($value);
        $value = openssl_encrypt($value, config('app.cipher'), $key, 0, $iv);
        $value = base64_encode($value);

        return $value;
    }
}

if (! function_exists('appdecrypt')) {
    function appdecrypt($value)
    {
        $key   = hash('sha256', config('app.key'));
        $iv    = substr($key, 0, openssl_cipher_iv_length(config('app.cipher')));

        $value = base64_decode($value);
        $value = openssl_decrypt($value, config('app.cipher'), $key, 0, $iv);
        $value = unserialize($value);

        return $value;
    }
}

if (! function_exists('hash')) {
    function hash($iv, $value)
    {
        return hash_hmac('sha256', $iv . $value, config('app.key'));
    }
}
