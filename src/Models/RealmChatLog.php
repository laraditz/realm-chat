<?php

namespace Laraditz\RealmChat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RealmChatLog extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'source', 'request', 'response', 'error_message'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'request' => 'json',
        'response' => 'json',
    ];

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = $model->id ?? (string) Str::orderedUuid();
        });
    }
}
