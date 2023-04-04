<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Post;
use App\Models\Comment;
use App\Transformers\Client\CommentTransformer;
use App\Http\Requests\Comments\CreateCommentRequest;
use App\Jobs\CreateCommentNotifyJob;

class CommentController extends ApiController
{
    /**
     * Create new comment for publish post
     *
     * @var App\Models\Post $post
     * @var App\Http\Requests\Comments\CreateCommentRequest $request
     *
     * @return json
     */
    public function store(Post $post, CreateCommentRequest $request)
    {
        if ($post->status == 'publish' && $post->publish_at < now()) {
            $data = [
                'content' => $request->input('content'),
                'posts_id' => $post->id,
            ];
            $comment = Comment::create($data);
            dispatch(new CreateCommentNotifyJob(auth()->user(), $post));

            return $this->response()
                ->attach($comment->load('owner'), new CommentTransformer, ['owner'])
                ->success();
        }

        return $this->response()
            ->withMessage(__('Không tìm thấy bài viết'), 404);
    }

    /**
     * Update user's comment
     *
     * @var App\Models\Post $post
     * @var App\Models\Comment $comment
     * @var App\Http\Requests\Comments\CreateCommentRequest $request
     *
     * @return json
     */
    public function update(Post $post, Comment $comment, CreateCommentRequest $request)
    {
        if ($post->status == 'publish' && $post->publish_at < now()) {
            $this->authorize('modify', $comment);
            $comment->update(['content' => $request->input('content')]);

            return $this->response()
                ->attach($comment->load('owner'), new CommentTransformer, ['owner'])
                ->success();
        }

        return $this->response()
            ->withMessage(__('Không tìm thấy bài viết'), 404);
    }

    /**
     * Delete user's comment
     *
     * @var App\Models\Post $post
     * @var App\Models\Comment $comment
     *
     * @return json
     */
    public function destroy(Post $post, Comment $comment)
    {
        if ($post->status == 'publish' && $post->publish_at < now()) {
            $this->authorize('modify', $comment);
            $comment->delete();

            return $this->response()
                ->deleted();
        }

        return $this->response()
            ->withMessage(__('Không tìm thấy bài viết'), 404);
    }
}
