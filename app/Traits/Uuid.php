<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Uuid
{
    /**
     * Model boot method
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }
}
