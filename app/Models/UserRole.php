<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Model\{
    ColumnFilterer, ColumnSorter, FindBy
};

class UserRole extends Model
{
    use ColumnFilterer;
    use ColumnSorter;
    use FindBy;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_roles';

    /**
     * Indicates if the IDs are primary-key.
     *
     * @var string
     */
    protected $primaryKey = null;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'role_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];
}
