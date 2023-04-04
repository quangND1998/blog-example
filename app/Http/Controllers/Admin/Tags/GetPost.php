<?php

namespace App\Http\Controllers\Admin\Tags;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Tag;
use App\Repositories\TagRepository;
use App\Transformers\Admin\PostTransformer;

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
     * Get my post by tag
     *
     * @var App\Models\Tag $tag
     * @var Illuminate\Http\Request $request
     *
     * @return json
     */
    public function __invoke(Tag $tag, Request $request)
    {
        $status = $request->query('status');
        $posts = $this->tag->getMinePostByStatus(auth()->user()->id, $tag->id, $status);

        return $this->response()
            ->attach($posts, new PostTransformer, ['owner', 'coverImage', 'serie', 'tags', 'votes', 'commentCount'])
            ->success();
    }
}
