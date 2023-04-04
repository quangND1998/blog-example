<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopTag extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'top_tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order',
        'tags_id',
    ];
}
