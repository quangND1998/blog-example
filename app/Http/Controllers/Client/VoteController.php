<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Post;
use App\Models\Vote;
use App\Http\Requests\Votes\VoteRequest;
use App\Jobs\CreateUpvoteNotifyJob;

class VoteController extends ApiController
{
    /**
     * Store vote value
     *
     * @var App\Models\Post $post
     * @var App\Http\Requests\Votes\VoteRequest $request
     *
     * @return json
     */
    public function store(Post $post, VoteRequest $request)
    {
        if ($post->status == 'publish' && $post->publish_at < now()) {
            $value = $request->input('value');
            $userId = auth()->user()->id;
            if ($userId == $post->users_id) {
                return $this->response()
                    ->withMessage(__('Bạn không thể tự upvote cho cho mình'), 422);
            }
            Vote::updateOrCreate(
                [
                    'posts_id' => $post->id,
                    'users_id' => $userId,
                ],
                [
                    'posts_id' => $post->id,
                    'users_id' => $userId,
                    'value' => $value,
                ]
            );
            dispatch(new CreateUpvoteNotifyJob($post->owner, $post, $value));

            return $this->response()
                ->success();
        }

        return $this->response()
            ->withMessage(__('Không tìm thấy bài viết'), 404);
    }
}
