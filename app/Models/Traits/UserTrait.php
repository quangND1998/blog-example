<?php

namespace App\Models\Traits;

trait UserTrait
{
    /**
     * Check if user is admin
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->role == 'admin';
    }

    /**
     * Check if user is author
     *
     * @return boolean
     */
    public function isAuthor()
    {
        return $this->role == 'author';
    }

    /**
     * Check if user is user
     *
     * @return boolean
     */
    public function isUser()
    {
        return $this->role == 'user';
    }

    /**
     * Check if user own serie by serie's id
     *
     * @var int $serieId
     *
     * @return Illuminate\Support\Collection
     */
    public function ownSerie($serieId)
    {
        return $this->series()->where('id', $serieId)->first();
    }
}
