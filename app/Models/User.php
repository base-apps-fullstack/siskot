<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Log\AuditLog;
use App\Models\Log\UserLog;
use App\Models\UserRole;
use App\Traits\Model\{
    ColumnFilterer, ColumnSorter, FindBy
};
use App\Libraries\Auth;

class User extends Model
{
    use SoftDeletes;
    use ColumnFilterer;
    use ColumnSorter;
    use FindBy;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            AuditLog::trackCreate($model);

            $data = $model->setAppends([])->toArray();
            if (!is_null($model->password)) {
                $data['password'] = $model->password;
            }

            $auth = optional(Auth::info());
            UserLog::create([
                'user_id' => $model->id,
                'by_user_id' => $auth->user_id ? $auth->user_id : $model->id,
                'type' => 'created',
                'data' => $data
            ]);
        });

        static::updated(function ($model) {
            AuditLog::trackUpdate($model);

            $auth = optional(Auth::info());
            if ($auth) {
                //create log
                $data = [];
                if (isset($model->original['username']) && $model->username != appdecrypt($model->original['username'])) {
                    $data['username'] = ['from' => appdecrypt($model->original['username']), 'to' => $model->username];
                }
                if (isset($model->original['fullname']) && $model->fullname != $model->original['fullname']) {
                    $data['fullname'] = ['from' => $model->original['fullname'], 'to' => $model->fullname];
                }
                if (isset($model->original['email']) && $model->email != $model->original['email']) {
                    $data['email'] = ['from' => $model->original['email'], 'to' => $model->email];
                }
                if (isset($model->original['image']) && $model->image != $model->original['image']) {
                    $data['image'] = ['from' => $model->original['image'], 'to' => $model->image];
                }
                if (isset($model->original['status']) && $model->status != $model->original['status']) {
                    $data['status'] = ['from' => $model->original['status'], 'to' => $model->status];
                }

                if (count($data) > 0) {
                    UserLog::create([
                        'user_id' => $model->id,
                        'by_user_id' => $auth->user_id,
                        'type' => 'updated',
                        'data' => $data
                    ]);
                }
            }
        });

        static::deleted(function ($model) {
            AuditLog::trackDelete($model);

            $auth = optional(Auth::info());
            UserLog::create([
                'user_id' => $model->id,
                'by_user_id' => $auth->user_id,
                'type' => 'deleted',
                'data' => $model
            ]);
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'fullname',
        'email',
        'image',
        'status',
        'password',
        'last_login',
        'login_attempt',
        'login_attempt_at',
        'locked_at',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes encrypted.
     *
     * @var array
     */
    protected $encrypted = [
        'username'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'image_url'
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

    /**
     * Get the areas for the user.
     */
    public function roles()
    {
        return $this->hasMany(UserRole::class, 'user_id', 'id');
    }

    /**
     * Set the username attribute.
     *
     * @param  string  $value
     * @return void
     */
    public function setUsernameAttribute($value)
    {
        if (is_null($value) == false) {
            if (appdecrypt($value) === false) {
                $this->attributes['username'] = appencrypt(strtolower($value));
            } else {
                $this->attributes['username'] = $value;
            }
        }
    }

    /**
     * Get the username attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getUsernameAttribute($value)
    {
        if ($decrypt = appdecrypt($value)) {
            return $decrypt;
        } else {
            return $value;
        }
    }

    /**
     * Get the image url attribute.
     *
     * @return url
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? \Storage::url($this->image) : asset('img/avatar.jpg') ;
    }

    /**
     * Get the role id attribute.
     *
     * @return integer
     */
    public function getRoleIdAttribute()
    {
        if ($roles = \DB::table('user_roles')->select('role_id')->where('user_id', $this->id)->first()) {
            return $roles->role_id;
        }
        
        return null;
    }

    /**
     * Get the role name attribute.
     *
     * @return string
     */
    public function getRoleNameAttribute()
    {
        if ($role = \DB::table('user_roles')->join('roles', 'roles.id', '=', 'user_roles.role_id')->select('roles.name')->where('user_roles.user_id', $this->id)->first()) {
            return $role->name;
        }

        return null;
    }
}
