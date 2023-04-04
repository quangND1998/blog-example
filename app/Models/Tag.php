<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tags';

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
        'images_id',
    ];

    /**
     * Get the cover image of the tag
     */
    public function thumbnailImage()
    {
        return $this->belongsTo(Image::class, 'images_id');
    }

    /**
     * Get the posts that belong to the tag
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tag', 'tags_id', 'posts_id');
    }

    /**
     * Mutate name value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    /**
     * Mutate slug value
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value);
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
