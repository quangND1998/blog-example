<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'origin_name',
        'extension',
        'type',
        'users_id',
    ];

    /**
     * Bootstrap model
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->users_id = auth()->user()->id;
        });
    }

    /**
     * Get the user that owns the image
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    /**
     * Get image public path
     *
     * @return string
     */
    public function getPath()
    {
        return env('APP_URL')
            . '/storage/images/'
            . $this->uuid
            . '.'
            . $this->extension;
    }
}
