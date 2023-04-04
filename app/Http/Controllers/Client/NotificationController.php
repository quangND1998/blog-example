<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Transformers\Client\NotificationTransformer;
use App\Http\Controllers\ApiController;

class NotificationController extends ApiController
{
    /**
     * Get notification list
     *
     * @return json
     */
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(10);

        return $this->response()
            ->attach($notifications, new NotificationTransformer)
            ->success();
    }
}
