<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Post;
use App\Models\Bookmark;
use App\Repositories\BookmarkRepository;
use App\Repositories\UserRepository;
use App\Transformers\Client\PostTransformer;
use App\Jobs\CreateBookmarkNotifyJob;

class BookmarkController extends ApiController
{
    protected $bookmark;
    protected $user;

    /**
     * Constructor
     */
    public function __construct(
        BookmarkRepository $bookmarkRepository,
        UserRepository $userRepository
    ) {
        $this->bookmark = $bookmarkRepository;
        $this->user = $userRepository;
    }

    public function index()
    {
        $posts = $this->user->getBookmarkList(auth()->user()->id);

        return $this->response()
            ->attach($posts, new PostTransformer, ['owner', 'coverImage', 'serie', 'tags', 'votes', 'commentCount'])
            ->success();
    }

    /**
     * Store bookmark
     *
     * @var App\Models\Post $post
     *
     * @return json
     */
    public function store(Post $post)
    {
        if ($post->status == 'publish' && $post->publish_at < now()) {
            if (auth()->user()->id == $post->users_id) {
                return $this->response()
                    ->withMessage(__('Bạn không thể tự lưu bài viết của mình'), 422);
            }
            Bookmark::updateOrCreate(
                [
                    'posts_id' => $post->id,
                    'users_id' => auth()->user()->id,
                ],
                [
                    'posts_id' => $post->id,
                ]
            );
            dispatch(new CreateBookmarkNotifyJob(auth()->user(), $post));

            return $this->response()
                ->success();
        }
        return $this->response()
            ->withMessage(__('Không tìm thấy bài viết'), 404);
    }

    /**
     * Unbookmark a post
     *
     * @var App\Models\Post $post
     *
     * @return none
     */
    public function destroy(Post $post)
    {
        $this->bookmark->remove(auth()->user()->id, $post->id);

        return $this->response()
            ->deleted();
    }
}
