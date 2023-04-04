<?php

namespace App\Repositories;

use App\Models\Notification;
use Carbon\Carbon;

class NotificationRepository extends BaseRepository
{
    /**
     * Init model associate with this repository
     */
    protected function model()
    {
        return new Notification;
    }

    public function markAllAsRead($userId)
    {
        return $this->model()
            ->where('users_id', $userId)
            ->update(['read_at' => Carbon::now()->format('Y-m-d')]);
    }
}
