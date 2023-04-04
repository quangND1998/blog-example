<?php

namespace App\Http\Controllers\Admin\Posts;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Posts\UpdatePostStatusRequest;

class UpdateStatus extends ApiController
{
    /**
     * Update post status
     *
     * @var App\Models\Post $post
     * @var App\Http\Requests\Posts\UpdatePostStatusRequest $request
     *
     * @return json
     */
    public function __invoke(Post $post, UpdatePostStatusRequest $request)
    {
        $this->authorize('modify', $post);
        $newStatus = $request->input('status');
        $result = $post->update(['status' => $newStatus]);

        return $this->response()
            ->success(__('Cập nhật trạng thái thành công'));
    }
}
