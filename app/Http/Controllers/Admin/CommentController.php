<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Repositories\CommentRepository;
use App\Transformers\Admin\CommentTransformer;

class CommentController extends ApiController
{
    protected $comment;

    /**
     * Constructor
     */
    public function __construct(CommentRepository $commentRepository)
    {
        $this->comment = $commentRepository;
    }

    /**
     * Get comment list
     *
     * @return json
     */
    public function index()
    {
        $comments = $this->comment->getList();

        return $this->response()
            ->attach($comments, new CommentTransformer, ['owner', 'post'])
            ->success();
    }
}
