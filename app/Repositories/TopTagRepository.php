<?php

namespace App\Repositories;

use App\Models\TopTag;

class TopTagRepository extends BaseRepository
{
    /**
     * Init model associate with this repository
     */
    protected function model()
    {
        return new TopTag;
    }
}
