<?php

namespace App\Transformers\Client;

use League\Fractal\TransformerAbstract;
use App\Transformers\TraitTransformer;
use App\Models\Notification;
use Carbon\Carbon;

class NotificationTransformer extends TransformerAbstract
{
    use TraitTransformer;

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Notification $notification)
    {
        Carbon::setLocale('vi');

        return [
            'id' => $notification->id,
            'link' => $notification->link,
            'content' => $notification->content,
            'type' => $notification->type,
            'readAt' => $notification->read_at,
            'createdAt' => Carbon::create($notification->created_at->format('d-m-Y'))
                ->diffForHumans(),
        ];
    }
}
