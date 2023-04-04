<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\PostRepository;
use App\Transformers\Client\PostTransformer;
use App\Models\Post;

class PostController extends ApiController
{
    protected $post;

    /**
     * Constructor
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->post = $postRepository;
    }

    /**
     * Get publish post list
     *
     * @return json
     */
    public function index(Request $request)
    {
        $query = $request->query('search');
        if ($query) {
            $posts = $this->post->searchPublish($query);
        } else {
            $posts = $this->post->getPublish();
        }

        return $this->response()
            ->attach(
                $posts,
                new PostTransformer,
                ['owner', 'coverImage', 'serie', 'tags', 'votes', 'commentCount']
            )->success();
    }

    /**
     * Get publish post detail
     *
     * @var App\Models\Post $post
     *
     * @return json
     */
    public function show(Post $post)
    {
        if ($post->status == 'publish' && $post->publish_at < now()) {
            $post->load(['owner', 'coverImage', 'serie', 'tags', 'votes', 'comments']);
            $this->countView($post);

            return $this->response()
                ->attach(
                    $post,
                    new PostTransformer,
                    ['owner', 'coverImage', 'serie', 'tags', 'votes', 'commentCount']
                )->success();
        }

        return $this->response()
            ->withMessage(__('Không tìm thấy bài viết'), 404);
    }

    /**
     * Count user view
     *
     * @var App\Models\Post $post
     *
     * @return void
     */
    private function countView($post)
    {
        logger(1);
        logger(session()->get($post->hash_id));
        if (session()->has($post->hash_id)) {
            if (session()->get($post->hash_id) < now()->getTimestamp()) {
                $post->increment('view_count');
                session()->put($post->hash_id, now()->addMinutes(5)->getTimestamp());
            }
        } else {
            $post->increment('view_count');
            session()->put($post->hash_id, now()->addMinutes(5)->getTimestamp());
        }
    }
}
