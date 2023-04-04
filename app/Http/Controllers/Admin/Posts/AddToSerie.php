<?php

namespace App\Http\Controllers\Admin\Posts;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Post;
use App\Http\Requests\Posts\AddPostToSeriesRequest;
use Illuminate\Auth\Access\AuthorizationException;

class AddToSerie extends ApiController
{
    /**
     * Add post to series
     *
     * @var App\Models\Post $post
     * @var App\Http\Requests\Posts\AddPostToSeriesRequest $request
     *
     * @return json
     */
    public function __invoke(Post $post, AddPostToSeriesRequest $request)
    {
        $this->authorize('modify', $post);
        $serieId = $request->input('serieId');
        if (auth()->user()->ownSerie($serieId)) {
            $post->update(['series_id' => $serieId]);

            return $this->response()
                ->success(__('Thêm bài viết vào series thành công'));
        } else {
            throw new AuthorizationException(0);
        }
    }
}
