<?php

namespace App\Http\Controllers\Admin\Series;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Serie;
use App\Repositories\PostRepository;
use App\Transformers\Admin\PostTransformer;

class GetPost extends ApiController
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
     * Get my post by serie id
     *
     * @var App\Models\Serie $serie
     * @var Illuminate\Http\Request $request
     *
     * @return json
     */
    public function __invoke(Serie $serie, Request $request)
    {
        $this->authorize('modify', $serie);
        $status = $request->query('status');
        $posts = $this->post->getMineBySerieAndStatus(auth()->user()->id, $serie->id, $status);

        return $this->response()
            ->attach($posts, new PostTransformer, ['owner', 'coverImage', 'serie', 'tags', 'votes', 'commentCount'])
            ->success();
    }
}
