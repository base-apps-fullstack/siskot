<?php

namespace App\Libraries;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\TokenTime;
use App\Libraries\JwtAuth;
use App\Models\User;
use App\Models\AuthToken;

class Auth
{
    /**
     * Middleware auth service
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public static function middleware(Request $request, Closure $next)
    {
        $check = self::info();

        if (is_null($check)) {
            return response()->json(['message' => 'Tidak ada otorisasi'], 401);
        }

        return $next($request);
    }

    /**
     * Get auth info
     *
     * @return \stdClass
     */
    public static function info()
    {
        $auth   = self::getAuthorization();
        $token  = self::getToken($auth);

        $data = optional(JwtAuth::decode($token));

        $result = $data->data;

        $check = \DB::table('auth_tokens')
            ->where('token', self::token())
            ->exists();
        
        if (!$check) {
            return null;
        }

        return $result;
    }

    /**
     * Get uer token
     *
     * @return string
     */
    public static function token()
    {
        $auth   = self::getAuthorization();
        $token  = self::getToken($auth);

        return sha1($token);
    }

    /**
     * Get authorization header
     *
     * @return mixed
     */
    private static function getAuthorization()
    {
        return isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : false;
    }

    /**
     * Get token
     *
     * @param string $authorization
     * @return string
     */
    private static function getToken(string $authorization)
    {
        return substr($authorization, 7);
    }

    /**
     * Get auth user info
     *
     * @return mixed
     */
    public static function user()
    {
        $info = self::info();

        if (is_null($info)) {
            return null;
        }

        return User::find($info->user_id);
    }

    /**
     * check auth user
     *
     * @return bool
     */
    public static function check()
    {
        $check = self::info();

        if (is_null($check)) {
            return false;
        }

        return true;
    }

    /**
     * Create token
     *
     * @return array
     */
    protected static function createToken($userId)
    {
        $expiredTime = TokenTime::for();
        $expiredTimeTimestamps =  (date('Y-m-d H:i:s', strtotime($expiredTime)));

        $payload = [
            'user_id'       => $userId
        ];
        
        $token = JwtAuth::encode($payload, $expiredTimeTimestamps);
        $encrypted  = sha1($token);

        return [
            'token'     => $token,
            'encrypted' => $encrypted,
        ];
    }

    /**
     * Generate token
     *
     * @return array
     */
    public static function generateToken($userId)
    {
        $token = self::createToken($userId);

        \DB::table('auth_tokens')->where([
            'user_id'       => $userId
        ])->delete();

        $store = AuthToken::storeToken($userId, $token['encrypted'], TokenTime::for());
        
        if ($store == false) {
            throw new \Exception("Gagal generate token");
        }

        return $token['token'];
    }

    /**
     * Refresh token
     *
     * @return JsonResponse
     */
    public static function refreshToken()
    {
        $user  = self::info();

        \DB::table('auth_tokens')
            ->where('user_id', $user->user_id)
            ->delete();

        $createToken = self::createToken($user->user_id);
        $token = AuthToken::storeToken($user->user_id, $createToken['encrypted'], TokenTime::for());

        return $createToken['token'];
    }
}
