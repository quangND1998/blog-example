<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    /**
     * Init model associate with this repository
     */
    protected function model()
    {
        return new User;
    }

    /**
     * Get user by their socialite info
     *
     * @var string $socialiteId
     * @var string $socialiteProvider
     *
     * @return Illuminate\Support\Collection
     */
    public function getBySocialiteInfo($socialiteId, $socialiteProvider)
    {
        return $this->model()
            ->where([
                ['socialite_id', $socialiteId],
                ['socialite_provider', $socialiteProvider],
            ])
            ->first();
    }

    /**
     * Get user list by condition
     *
     * @var array $condition
     * @var int $perPage
     *
     * @return Illuminate\Support\Collection
     */
    public function getListByCondition($condition, $perPage = 15)
    {
        if (sizeof($condition)) {
            $query = $this->model()
                ->where($condition);
        } else {
            $query = $this->model();
        }

        return $query->with(['votes'])
            ->withCount(['posts', 'images', 'series', 'comments', 'votes'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get bookmark post list of by user id
     *
     * @var int $userId
     *
     * @return Illuminate\Support\Collection
     */
    public function getBookmarkList($userId, $perPage = 15)
    {
        return $this->model()
            ->where('id', $userId)
            ->first()
            ->bookmarks()
            ->publish()
            ->orderBy('publish_at', 'DESC')
            ->paginate($perPage);
    }
}
