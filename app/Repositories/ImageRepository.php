<?php

namespace App\Repositories;

use App\Models\Image;

class ImageRepository extends BaseRepository
{
    /**
     * Init model associate with this repository
     */
    protected function model()
    {
        return new Image;
    }

    /**
     * Get image list by owner
     *
     * @var array $condition
     * @var int $perPage
     *
     * @return Illuminate\Support\Collection
     */
    public function getMyList($userId, $condition = [], $perPage = 15)
    {
        return $query = $this->model()
            ->where('users_id', $userId)
            ->where($condition)
            ->latest()
            ->paginate($perPage);
    }
}
