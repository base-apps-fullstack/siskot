<?php

namespace App\Libraries;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;

class JwtAuth
{
    /**
     * Encode token
     *
     * @param array $data
     * @return string
     */
    public static function encode($data, $expiredTime)
    {
        return JWT::encode(self::payload($data, $expiredTime), self::key());
    }

    /**
     * Decode token
     *
     * @param string $token
     * @return mixed
     */
    public static function decode($token)
    {
        try {
            return JWT::decode($token, self::key(), array('HS256'));
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Payload
     *
     * @param string $data
     * @param timestamps $data
     * @return array
     */
    private static function payload($data, $expiredTime)
    {
        return [
            "iss"   => config('jwt.iss'),
            "aud"   => config('jwt.aud'),
            "iat"   => strtotime("now"),
            'data'  => $data
        ];
    }

    /**
     * Get key token
     *
     * @return string
     */
    private static function key()
    {
        return config('jwt.key');
    }
}
