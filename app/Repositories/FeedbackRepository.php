<?php

namespace App\Repositories;

use App\Models\Feedback;

class FeedbackRepository extends BaseRepository
{
    /**
     * Init model associate with this repository
     */
    protected function model()
    {
        return new Feedback;
    }

    /**
     * Get feedback list by condition
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

        return $query->latest()
            ->paginate($perPage);
    }
}
