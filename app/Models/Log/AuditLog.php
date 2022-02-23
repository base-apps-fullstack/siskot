<?php

namespace App\Models\Log;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use App\Libraries\Auth;

class AuditLog extends Model
{
    const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'audit_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'table', 'table_keyname', 'table_key', 'user_id', 'data'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Track Create.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $data
     * @param int $user_id
     * @param string $table_key
     * @param string $table_keyname
     * @param string $table
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function trackCreate(
        Model $model,
        array $data = null,
        $user_id = null,
        $table_key = null,
        $table_keyname = null,
        $table = null
    ) {
        $datas = $model->setAppends([])->toArray();

        $auth = optional(Auth::info());
        return self::create([
            'type'          => 'create',
            'table'         => (is_null($table)         ? $model->getTable()    : $table),
            'table_keyname' => (is_null($table_keyname) ? $model->getKeyName()  : $table_keyname),
            'table_key'     => (is_null($table_key)     ? $model->getKey()      : $table_key),
            'user_id'       => (is_null($user_id)       ? $auth->user_id        : $user_id),
            'data'          => (is_null($data)          ? $datas                : $data)
        ]);
    }

    /**
     * Track Update.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $data
     * @param int $user_id
     * @param string $table_key
     * @param string $table_keyname
     * @param string $table
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function trackUpdate(
        Model $model,
        array $data = null,
        $user_id = null,
        $table_key = null,
        $table_keyname = null,
        $table = null
    ) {
        if (is_null($data)) {
            $data = $model->setAppends([])->toArray();
            if ($model->changes) {
                foreach (Arr::only($model->changes, $model->getFillable()) as $key => $value) {
                    if (isset($model->original[$key]) && $model->original[$key] != $value) {
                        $data[$key] = [
                            'from' => (json_decode($model->original[$key]) ? json_decode($model->original[$key]) : $model->original[$key]),
                            'to'   => (json_decode($value) ? json_decode($value) : $value),
                        ];
                    } else {
                        // if ($key == 'id_number') {
                        //     if (ayodecrypt($data['id_number'])===false) {
                        //         $data['id_number'] = ayoencrypt($data['id_number']);
                        //     }
                        // }
                    }
                }
            }
        }

        $auth = optional(Auth::info());
        $userId = (is_null($user_id) ? $auth->user_id : $user_id);

        if ($userId) {
            return self::create([
                'type'          => 'update',
                'table'         => (is_null($table)         ? $model->getTable()    : $table),
                'table_keyname' => (is_null($table_keyname) ? $model->getKeyName()  : $table_keyname),
                'table_key'     => (is_null($table_key)     ? $model->getKey()      : $table_key),
                'user_id'       => $userId,
                'data'          => $data
            ]);
        }
        return true;
    }

    /**
     * Track Delete.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $data
     * @param int $user_id
     * @param string $table_key
     * @param string $table_keyname
     * @param string $table
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function trackDelete(
        Model $model,
        array $data = null,
        $user_id = null,
        $table_key = null,
        $table_keyname = null,
        $table = null
    ) {
        $datas = $model->setAppends([])->toArray();

        $auth = optional(Auth::info());
        return self::create([
            'type'          => 'delete',
            'table'         => (is_null($table)         ? $model->getTable()    : $table),
            'table_keyname' => (is_null($table_keyname) ? $model->getKeyName()  : $table_keyname),
            'table_key'     => (is_null($table_key)     ? $model->getKey()      : $table_key),
            'user_id'       => (is_null($user_id)       ? $auth->user_id        : $user_id),
            'data'          => (is_null($data)          ? $datas : $data)
        ]);
    }
}
