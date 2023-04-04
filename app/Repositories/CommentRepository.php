<?php

namespace App\Repositories;

use App\Models\Comment;

class CommentRepository extends BaseRepository
{
    /**
     * Init model associate with this repository
     */
    protected function model()
    {
        return new Comment;
    }

    /**
     * Get comment list
     *
     * @var int $perPage
     *
     * @return Illuminate\Support\Collection
     */
    public function getList($perPage = 15)
    {
        return $this->model()
            ->with(['owner', 'post'])
            ->latest()
            ->paginate($perPage);
    }
}
