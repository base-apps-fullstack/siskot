<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Menu;
use App\Models\User;
use App\Traits\Model\{
    ColumnFilterer, ColumnSorter, FindBy
};

class Role extends Model
{
    use ColumnFilterer;
    use ColumnSorter;
    use FindBy;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'user_id',
        'permissions'
    ];

    /**
     * The attributes that should be cast for arrays.
     *
     * @var array
     */
    protected $casts = [
        'permissions' => 'array',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
    * Append attribute
    * @var array
    */
    protected $appends = [
        'editable'
    ];

    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles')->withTimestamps();
    }

    /**
     * relation with menus
     */
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    /**
    * Get editable status
    *
    * @return boolean
    */
    public function getEditableAttribute()
    {
        if (in_array($this->id, [1, 2, 3, 4, 5])) {
            return false;
        }

        return true;
    }
}
