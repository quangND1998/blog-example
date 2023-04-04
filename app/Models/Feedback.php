<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Feedback extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'feedbacks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hash_id',
        'email',
        'content',
        'status',
        'reply_content',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'hash_id';
    }

    /**
     * Bootstrap model
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->hash_id = substr(md5((string) Uuid::generate(4)), 0, 10);
        });
    }
}
