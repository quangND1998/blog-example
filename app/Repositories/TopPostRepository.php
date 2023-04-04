<?php

namespace App\Repositories;

use App\Models\TopPost;

class TopPostRepository extends BaseRepository
{
    /**
     * Init model associate with this repository
     */
    protected function model()
    {
        return new TopPost;
    }
}
