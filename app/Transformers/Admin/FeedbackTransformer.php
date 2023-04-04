<?php

namespace App\Transformers\Admin;

use League\Fractal\TransformerAbstract;
use App\Models\Feedback;
use Carbon\Carbon;

class FeedbackTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Feedback $feedback)
    {
        return [
            'id' => $feedback->id,
            'hashId' => $feedback->hash_id,
            'email' => $feedback->email,
            'content' => $feedback->content,
            'status' => $feedback->status,
            'reply' => $feedback->reply_content,
            'createdAt' => $feedback->created_at ? Carbon::parse($feedback->created_at)->format('d/m/Y H:i:s') : null,
            'updatedAt' => $feedback->updated_at ? Carbon::parse($feedback->updated_at)->format('d/m/Y H:i:s') : null,
        ];
    }
}
