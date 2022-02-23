<?php

namespace App\Models\Log;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'by_user_id', 'data', 'type'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];
}
