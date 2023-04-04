<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Transformers\Client\TagTransformer;
use App\Repositories\TagRepository;
use App\Models\Tag;

class TagController extends ApiController
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
     * Get tag list
     *
     * @var Illuminate\Http\Request $request
     *
     * @return json
     */
    public function index(Request $request)
    {
        $name = $request->query('name');
        $tags = $this->tag->getListByCondition($name);

        return $this->response()
            ->attach($tags, new TagTransformer, ['thumbnailImage'])
            ->success();
    }

    /**
     * Get single tag detail
     *
     * @var App\Models\Tag $tag
     *
     * @return json
     */
    public function show(Tag $tag)
    {
        return $this->response()
            ->attach($tag->load(['thumbnailImage']), new TagTransformer, ['thumbnailImage'])
            ->success();
    }
}
