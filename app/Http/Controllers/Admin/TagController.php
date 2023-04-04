<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Repositories\TagRepository;
use App\Models\Tag;
use App\Transformers\Admin\TagTransformer;
use App\Http\Requests\Tags\CreateTagRequest;
use App\Http\Requests\Tags\UpdateTagRequest;
use Webpatser\Uuid\Uuid;

class TagController extends ApiController
{
    protected $tag;
    protected $allowStoreField = [
        'name',
        'slug',
        'description',
        'images_id',
    ];

    /**
     * Constructor
     */
    public function __construct(TagRepository $tagRepository)
    {
        $this->tag = $tagRepository;
    }

    /**
     * Store new tag
     *
     * @var App\Http\Requests\Tags\CreateTagRequest $request
     *
     * @return json
     */
    public function store(CreateTagRequest $request)
    {
        $data = $request->only($this->allowStoreField);
        $data['hash_id'] = substr(md5((string) Uuid::generate(4)), 0, 10);
        $tag = Tag::create($data);

        return $this->response()
            ->attach($tag->load(['thumbnailImage']), new TagTransformer, ['thumbnailImage'])
            ->created(__('Tạo tag thành công'));
    }

    /**
     * Update given tag
     *
     * @var App\Models\Tag $tag
     * @var App\Http\Requests\Tag\UpdateTagRequest $request
     *
     * @return json
     */
    public function update(Tag $tag, UpdateTagRequest $request)
    {
        $data = $request->only($this->allowStoreField);
        $tag->update($data);

        return $this->response()
            ->attach($tag->load(['thumbnailImage']), new TagTransformer, ['thumbnailImage'])
            ->success(__('Cập nhật tag thành công'));
    }

    /**
     * Remove given tag
     *
     * @var App\Models\Tag $tag
     *
     * @return json
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return $this->response()
            ->deleted();
    }
}
