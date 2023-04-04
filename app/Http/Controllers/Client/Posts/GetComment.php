<?php

namespace App\Http\Controllers\Client\Posts;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Post;
use App\Transformers\Client\CommentTransformer;

class GetComment extends ApiController
{
    /**
     * Get comment list of given post
     *
     * @var App\Models\Post $post
     *
     * @return json
     */
    public function __invoke(Post $post)
    {
        $comments = $post->comments()
            ->with(['owner'])
            ->latest()
            ->paginate(10);

        return $this->response()
            ->attach($comments, new CommentTransformer, ['owner'])
            ->success();
    }
}
