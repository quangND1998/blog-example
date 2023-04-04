<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Webpatser\Uuid\Uuid;

class Post extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hash_id',
        'title',
        'slug',
        'preview',
        'content',
        'og_description',
        'status',
        'view_count',
        'share_count',
        'publish_at',
        'users_id',
        'series_id',
        'images_id',
    ];

    /**
     * Bootstrap model
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            if (!$model->images_id) {
                $model->images_id = config('const.DEFAULT_POST_COVER_IMAGE_ID');
            }
            $model->hash_id = substr(md5((string) Uuid::generate(4)), 0, 10);
            $model->users_id = auth()->user()->id;
        });
    }

    /**
     * Get the user that owns the post
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    /**
     * Get the serie that owns the post
     */
    public function serie()
    {
        return $this->belongsTo(Serie::class, 'series_id');
    }

    /**
     * Get the cover image of the post
     */
    public function coverImage()
    {
        return $this->belongsTo(Image::class, 'images_id');
    }

    /**
     * Get tag list of the post
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'posts_id', 'tags_id');
    }

    /**
     * Get vote list of the post
     */
    public function votes()
    {
        return $this->hasMany(Vote::class, 'posts_id', 'id');
    }

    /**
     * Get comment list of the post
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'posts_id', 'id');
    }

    /**
     * Get bookmark list of the post
     */
    public function bookmarks()
    {
        return $this->belongsToMany(User::class, 'bookmarks', 'posts_id', 'users_id');
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
     * Mutate slug value
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * Mutate publish at value
     */
    public function setPublishAtAttribute($value)
    {
        $this->attributes['publish_at'] = date('Y-m-d H:i:s', strtotime($value));
    }

    /**
     * Scope a query to only include publish post.
     *
     * @var  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublish($query)
    {
        return $query->where([
            ['status', 'publish'],
            ['publish_at', '<', now()],
        ]);
    }
}
