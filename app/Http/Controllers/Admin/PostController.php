<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Post;
use App\Transformers\Admin\PostTransformer;
use App\Http\Requests\Posts\CreatePostRequest;
use App\Http\Requests\Posts\UpdatePostRequest;
use App\Repositories\TagRepository;
use Illuminate\Support\Str;
use Webpatser\Uuid\Uuid;
use App\Repositories\PostRepository;
use App\_H\Enum\PostStatus;

class PostController extends ApiController
{
    protected $tag;
    protected $post;
    protected $allowStoreField = [
        'title',
        'slug',
        'preview',
        'content',
        'status',
        'publish_at',
        'series_id',
        'images_id',
    ];
    protected $allowFilter = [
        'title',
        'status',
    ];
    protected $allowOrder = [
        'view_count',
        'share_count',
    ];

    /**
     * Constructor
     */
    public function __construct(
        TagRepository $tagRepository,
        PostRepository $postRepository
    ) {
        $this->tag = $tagRepository;
        $this->post = $postRepository;
    }

    /**
     * Get my post list
     *
     * @var Illuminate\Http\Request $request
     *
     * @return json
     */
    public function index(Request $request)
    {
        if ($request->query('serieId')) {
            $posts = $this->post->getPublishNotInSerie($request->query('serieId'), $request->query('title'));
        } else {
            $condition = $this->constructFilter($request);
            $orderOption = $request->query('order');
            $order = in_array($orderOption, $this->allowOrder) ? $orderOption : '';
            $posts = $this->post->getMyListByCondition(auth()->user()->id, $condition, $order);
        }
        

        return $this->response()
            ->attach($posts, new PostTransformer, ['owner', 'coverImage', 'serie', 'tags', 'votes', 'commentCount'])
            ->success();
    }

    /**
     * Get my post detail
     *
     * @var App\Models\Post $post
     *
     * @return json
     */
    public function show(Post $post)
    {
        $this->authorize('modify', $post);

        return $this->response()
            ->attach(
                $post->load(['owner', 'coverImage', 'serie', 'tags', 'votes', 'comments']),
                new PostTransformer,
                ['owner', 'coverImage', 'serie', 'tags', 'votes', 'commentCount']
            )
            ->success();
    }

    /**
     * Create new post
     *
     * @var App\Http\Requests\Posts\CreatePostRequest $request
     *
     * @return json
     */
    public function store(CreatePostRequest $request)
    {
        $this->authorize('create', Post::class);
        $data = $request->only($this->allowStoreField);
        if ($data['status'] === PostStatus::DRAFT) {
            unset($data['publish_at']);
        }
        $post = Post::create($data);
        $tagList = $request->input('tags');
        $this->handleSyncTag($post, $tagList);

        return $this->response()
            ->attach(
                $post->load(['owner', 'coverImage', 'serie', 'tags', 'votes', 'comments']),
                new PostTransformer,
                ['owner', 'coverImage', 'serie', 'tags', 'votes', 'commentCount']
            )
            ->created(__('Tạo bài viết thành công'));
    }

    /**
     * Update given post
     *
     * @var App\Models\Post $post
     * @var App\Http\Requests\Posts\UpdatePostRequest $request
     *
     * @return json
     */
    public function update(Post $post, UpdatePostRequest $request)
    {
        $this->authorize('modify', $post);
        $data = $request->only($this->allowStoreField);
        $post->update($data);
        $tagList = $request->input('tags');
        $this->handleSyncTag($post, $tagList);

        return $this->response()
            ->attach(
                $post->load(['owner', 'coverImage', 'serie', 'tags', 'votes', 'comments']),
                new PostTransformer,
                ['owner', 'coverImage', 'serie', 'tags', 'votes', 'commentCount']
            )
            ->success(__('Cập nhật bài viết thành công'));
    }

    /**
     * Delete given post
     *
     * @var App\Models\Post $post
     *
     * @return none
     */
    public function destroy(Post $post)
    {
        $this->authorize('modify', $post);
        $post->delete();

        return $this->response()
                ->deleted();
    }

    /**
     * Construct filter condition from request
     *
     * @var Illuminate\Http\Request $request
     *
     * @return array
     */
    private function constructFilter(Request $request)
    {
        $condition = [];
        $filter = $request->only($this->allowFilter);
        if (isset($filter['title'])) {
            array_push($condition, ['title', 'like', '%' . $filter['title'] . '%']);
        }
        if (isset($filter['status'])) {
            array_push($condition, ['status', '=', $filter['status']]);
        }

        return $condition;
    }

    /**
     * Sync post's tag
     *
     * @var App\Models\Post $post
     * @var array $tagList
     *
     * @return void
     */
    private function handleSyncTag(Post $post, $tagList)
    {
        if (sizeof($tagList)) {
            $tagIds = $this->getTagId($tagList);
            $post->tags()->sync($tagIds);
        }
    }

    /**
     * Get given tag list's id
     *
     * @var array $tagList
     *
     * @return array
     */
    private function getTagId($tagList)
    {
        $lowercaseTagList = array_map('strtolower', $tagList);
        $existsTags = $this->tag->searchNameIn($lowercaseTagList);
        if (sizeof($tagList) == sizeof($existsTags)) {
            return $existsTags->pluck('id')->all();
        }

        $newTags = array_diff($lowercaseTagList, $existsTags->pluck('name')->all());
        $newTagsData = $this->formatTagData($newTags);
        if ($this->tag->storeMultiple($newTagsData)) {
            return $this->tag->searchNameIn($lowercaseTagList)->pluck('id')->all();
        }
    }

    /**
     * Re-format tag data to insert to database
     *
     * @var array $tagList
     *
     * @return array
     */
    private function formatTagData($tagList)
    {
        $result = [];
        foreach ($tagList as $key => $tagName) {
            $result[$key]['hash_id'] = substr(md5((string) Uuid::generate(4)), 0, 10);
            $result[$key]['name'] = $tagName;
            $result[$key]['slug'] = Str::slug($tagName);
        }

        return $result;
    }
}
