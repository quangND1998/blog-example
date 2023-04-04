<?php

namespace App\Http\Controllers\Client\Tags;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Tag;
use App\Repositories\TagRepository;
use App\Transformers\Client\PostTransformer;

class GetPost extends ApiController
{
    protected $tag;

    /**
     * Constructor
     */
    public function __construct(TagRepository $tagRepository)
    {
        $this->tag = $tagRepository;
    }

    /**
     * Get publish post by tag id
     *
     * @var App\Models\Tag $tag
     * @var Illuminate\Http\Request $request
     *
     * @return json
     */
    public function __invoke(Tag $tag, Request $request)
    {
        $posts = $this->tag->getPublishPost($tag->id);

        return $this->response()
            ->attach($posts, new PostTransformer, ['owner', 'coverImage', 'serie', 'tags', 'votes', 'commentCount'])
            ->success();
    }
}
