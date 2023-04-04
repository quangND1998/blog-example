<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Comment extends Model
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hash_id',
        'content',
        'posts_id',
        'users_id',
        'parent_id',
    ];

    /**
     * Bootstrap model
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->hash_id = substr(md5((string) Uuid::generate(4)), 0, 10);
            $model->users_id = auth()->user()->id;
        });
    }

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
     * Get the user that owns the comment
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    /**
     * Get the post that owns the comment
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'posts_id');
    }
}
