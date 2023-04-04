<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\UserTrait;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, UserTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hash_id',
        'socialite_id',
        'socialite_provider',
        'username',
        'email',
        'avatar',
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * Get the posts for the user
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'users_id');
    }

    /**
     * Get the images for the user
     */
    public function images()
    {
        return $this->hasMany(Image::class, 'users_id');
    }

    /**
     * Get the comments for the user
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'users_id');
    }

    /**
     * Get the notifications for the user
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'users_id');
    }

    /**
     * Get the claps for the user
     */
    public function votes()
    {
        return $this->hasMany(Vote::class, 'users_id');
    }

  /**
     * Get the series for the user
     */
    public function series()
    {
        return $this->hasMany(Serie::class, 'users_id');
    }

    /**
     * Get the bookmark that belong to the user
     */
    public function bookmarks()
    {
        return $this->belongsToMany(Post::class, 'bookmarks', 'users_id', 'posts_id');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'username';
    }
}
