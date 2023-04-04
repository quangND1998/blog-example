<?php

namespace App\Repositories;

use App\Models\Bookmark;

class BookmarkRepository extends BaseRepository
{
    /**
     * Init model associate with this repository
     */
    protected function model()
    {
        return new Bookmark;
    }

    /**
     * Remove bookmark post
     *
     * @var int $userId
     * @var int $postId
     *
     * @return boolean
     */
    public function remove($userId, $postId)
    {
        $this->model()
            ->where([
                ['users_id', $userId],
                ['posts_id', $postId],
            ])
            ->delete();
    }
}
