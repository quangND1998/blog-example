<?php

namespace App\Http\Controllers\Client\Notifications;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\NotificationRepository;

class MarkAllAsRead extends ApiController
{
    protected $notification;

    /**
     * Contructor
     */
    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notification = $notificationRepository;
    }

    /**
     * Mark all notification as read
     *
     * @return json
     */
    public function __invoke()
    {
        $this->notification->markAllAsRead(auth()->user()->id);

        return $this->response()
            ->success();
    }
}
