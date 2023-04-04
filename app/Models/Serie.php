<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class Serie extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'series';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hash_id',
        'name',
        'slug',
        'description',
        'users_id',
        'images_id',
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
     * Get the cover image of the serie
     */
    public function coverImage()
    {
        return $this->belongsTo(Image::class, 'images_id');
    }

    /**
     * Get the owner of the serie
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    /**
     * Get the posts associate with the serie
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'series_id');
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
}
