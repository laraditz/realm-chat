<?php

namespace Laraditz\RealmChat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laraditz\RealmChat\Enums\MessageType;
use Laraditz\RealmChat\Enums\MessageDirection;

class RealmChatMessage extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'message_id', 'device_id', 'phone_number', 'body', 'direction', 'type', 'media_url', 'error_message'];

    protected $casts = [
        'direction' => MessageDirection::class,
        'type' => MessageType::class,
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
