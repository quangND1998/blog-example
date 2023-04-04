<?php

namespace App\Repositories;

use App\Models\Tag;

class TagRepository extends BaseRepository
{
    /**
     * Init model associate with this repository
     */
    protected function model()
    {
        return new Tag;
    }

    /**
     * Search all exists tag by name
     *
     * @var array $tagList
     *
     * @return Illuminate\Support\Collection
     */
    public function searchNameIn($tagList)
    {
        return $this->model()
            ->whereIn('name', $tagList)
            ->get();
    }

    /**
     * Create multiple tag by name
     *
     * @var array $tagDataList
     *
     * @return Illuminate\Support\Collection
     */
    public function storeMultiple($tagDataList)
    {
        return $this->model()
            ->insert($tagDataList);
    }

    /**
     * Get post list by tag and owner
     *
     * @var int $tagId
     * @var int $userId
     * @var string $status
     * @var int $perPage
     *
     * @return Illuminate\Support\Collection
     */
    public function getMinePostByStatus($userId, $tagId, $status, $perPage = 15)
    {
        $query = $this->model()
            ->with(['thumbnailImage'])
            ->withCount('posts')
            ->where('id', $tagId)
            ->first()
            ->posts()
            ->with(['owner', 'coverImage', 'serie', 'tags', 'votes', 'comments'])
            ->where('users_id', $userId);
        if ($status == 'trashed') {
            $query = $query->onlyTrashed();
        } elseif ($status) {
            $query = $query->where('status', $status);
        } else {
            $query = $query->withTrashed();
        }

        return $query->latest()
            ->paginate($perPage);
    }

    /**
     * Get tag list by name
     *
     * @var string $name
     * @var int $perPage
     *
     * @return Illuminate\Support\Collection
     */
    public function getListByCondition($name = '', $perPage = 50)
    {
        return $this->model()
            ->with(['thumbnailImage'])
            ->withCount('posts')
            ->where('name', 'like', '%' . $name . '%')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get pubish post by tag id
     *
     * @var int $tagId
     * @var int $perPage
     *
     * @return Illuminate\Support\Collection
     */
    public function getPublishPost($tagId, $perPage = 15)
    {
        return $this->model()
            ->with(['thumbnailImage'])
            ->where('id', $tagId)
            ->first()
            ->posts()
            ->with(['owner', 'coverImage', 'serie', 'tags', 'votes', 'comments'])
            ->publish()
            ->orderBy('publish_at', 'DESC')
            ->paginate($perPage);
    }
}
