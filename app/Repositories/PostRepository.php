<?php

namespace App\Repositories;

use App\Models\Post;

class PostRepository extends BaseRepository
{
    /**
     * Init model associate with this repository
     */
    protected function model()
    {
        return new Post;
    }

    /**
     * Remove posts from given serie
     *
     * @var int $serieId
     *
     * @return int
     */
    public function removeSerie($serieId)
    {
        return $this->model()
            ->withTrashed()
            ->where('series_id', $serieId)
            ->update(['series_id' => null]);
    }

    /**
     * Get post list by condition
     *
     * @var string $status
     * @var array $condition
     * @var string $order
     * @var int $perPage
     *
     * @return Illuminate\Support\Collection
     */
    public function getListByCondition($condition, $order = '', $perPage = 15)
    {
        if ($order) {
            $query = $this->model()->orderBy($order, 'desc');
        } else {
            $query = $this->model();
        }
        return $query->with(['owner', 'coverImage', 'serie', 'tags', 'votes', 'comments'])
            ->where($condition)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get post list by condition and owner
     *
     * @var string $status
     * @var array $condition
     * @var string $order
     * @var int $perPage
     *
     * @return Illuminate\Support\Collection
     */
    public function getMyListByCondition($usserId, $condition, $order = '', $perPage = 15)
    {
        if ($order) {
            $query = $this->model()->orderBy($order, 'desc');
        } else {
            $query = $this->model();
        }
        return $query->with(['owner', 'coverImage', 'serie', 'tags', 'votes', 'comments'])
            ->where('users_id', $usserId)
            ->where($condition)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get publish post by serie
     *
     * @var int $serieId
     * @var int $perPage
     *
     * @return Illuminate\Support\Collection
     */
    public function getPublishBySerie($serieId, $perPage = 15)
    {
        return $this->model()
            ->with(['owner', 'coverImage', 'serie', 'tags', 'votes', 'comments'])
            ->publish()
            ->where('series_id', $serieId)
            ->orderBy('publish_at', 'DESC')
            ->paginate($perPage);
    }

    /**
     * Get post by serie and status
     *
     * @var int $serieId
     * @var string $status
     * @var int $perPage
     *
     * @return Illuminate\Support\Collection
     */
    public function getMineBySerieAndStatus($usserId, $serieId, $status = '', $perPage = 15)
    {
        if ($status == 'trashed') {
            $query = $this->model()
                ->onlyTrashed();
        } elseif ($status) {
            $query = $this->model()
                ->where('status', $status);
        } else {
            $query = $this->model()
                ->withTrashed();
        }

        return $query->with(['owner', 'coverImage', 'serie', 'tags', 'votes', 'comments'])
            ->where('series_id', $serieId)
            ->where('users_id', $usserId)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get publish post list
     *
     * @var int $perPage
     *
     * @return Illuminate\Support\Collection
     */
    public function getPublish($perPage = 15)
    {
        return $this->model()
            ->with(['owner', 'coverImage', 'serie', 'tags', 'votes', 'comments'])
            ->publish()
            ->orderBy('publish_at', 'DESC')
            ->paginate($perPage);
    }

    /**
     * Search publish post by title
     *
     * @var string $keyWord
     * @var int $perPage
     *
     * @return Illuminate\Support\Collection
     */
    public function searchPublish($keyWord, $perPage = 15)
    {
        return $this->model()
            ->where('title', 'like', '%' . $keyWord . '%')
            ->with(['owner', 'coverImage', 'serie', 'tags', 'votes', 'comments'])
            ->publish()
            ->paginate($perPage);
    }

    /**
     * Get ppublish post not in serie
     *
     * @var int $serieId
     * @var string $postTitle
     * @var int $perPage
     *
     * @return Illuminate\Support\Collection
     */
    public function getPublishNotInSerie($serieId, $postTitle = '', $perPage = 5)
    {
        return $this->model()
            ->with(['owner', 'coverImage', 'serie', 'tags', 'votes', 'comments'])
            ->where('title', 'like', '%' . $postTitle . '%')
            ->where(function ($query) use ($serieId) {
                $query->where('series_id', '!=', $serieId);
                $query->orWhere('series_id', null);
            })
            ->latest()
            ->paginate($perPage);
    }
}
