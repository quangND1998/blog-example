<?php

namespace App\Repositories;

use App\Models\Serie;

class SerieRepository extends BaseRepository
{
    /**
     * Init model associate with this repository
     */
    protected function model()
    {
        return new Serie;
    }

    /**
     * Get series list by name
     *
     * @var string $name
     * @var int $perPage
     *
     * @return Illuminate\Support\Collection
     */
    public function getListByCondition($name = '', $perPage = 10)
    {
        return $this->model()
            ->with(['owner', 'coverImage'])
            ->withCount('posts')
            ->where('name', 'like', '%' . $name . '%')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get my series list by name
     *
     * @var string $name
     * @var int $perPage
     *
     * @return Illuminate\Support\Collection
     */
    public function getMyListByCondition($userId, $name = '', $perPage = 10)
    {
        return $this->model()
            ->with(['owner', 'coverImage'])
            ->withCount('posts')
            ->where('name', 'like', '%' . $name . '%')
            ->where('users_id', $userId)
            ->latest()
            ->paginate($perPage);
    }
}
