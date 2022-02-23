<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthToken extends Model
{

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'auth_tokens';

    /**
     * Fillable field
     *
     * @var array
     */
    protected $fillable = [
        'token',
        'user_id',
        'expires_at'
    ];

    /**
     * Hidden field
     */
    protected $hidden = ['token'];

    /**
     * Primary key
     *
     * @var string
     */
    protected $primaryKey = 'token';

    /**
     * Create token
     *
     * @var string
     */
    public static function storeToken($userId, $token, $expiresAt)
    {
        $store = self::create([
            'user_id'       => $userId,
            'token'         => $token,
            'expires_at'    => $expiresAt
        ]);

        return $store;
    }
}
