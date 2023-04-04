<?php

namespace App\Http\Controllers\Admin\Posts;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Post;
use App\Http\Requests\Posts\RemovePostFromSerieRequest;
use Illuminate\Auth\Access\AuthorizationException;

class RemoveFromSerie extends ApiController
{
    /**
     * Add post to series
     *
     * @var App\Models\Post $post
     * @var App\Http\Requests\Posts\RemovePostFromSerieRequest $request
     *
     * @return json
     */
    public function __invoke(Post $post, RemovePostFromSerieRequest $request)
    {
        $this->authorize('modify', $post);
        $serieId = $request->input('serieId');
        if ($post->series_id == $serieId) {
            $post->update(['series_id' => null]);
        }


        return $this->response()
            ->success(__('Xóa bài viết khỏi series thành công'));
    }
}
