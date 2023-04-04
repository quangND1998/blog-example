<?php

namespace App\Http\Controllers\Client\Series;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Serie;
use App\Repositories\PostRepository;
use App\Transformers\Client\PostTransformer;

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
     * Get publish post by serie id
     *
     * @var App\Models\Serie $serie
     * @var Illuminate\Http\Request $request
     *
     * @return json
     */
    public function __invoke(Serie $serie, Request $request)
    {
        $posts = $this->post->getPublishBySerie($serie->id);

        return $this->response()
            ->attach($posts, new PostTransformer, ['owner', 'coverImage', 'serie', 'tags', 'votes', 'commentCount'])
            ->success();
    }
}
