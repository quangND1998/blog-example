<?php

namespace App\Transformers\Admin;

use League\Fractal\TransformerAbstract;
use App\Transformers\TraitTransformer;
use App\Models\Comment;
use League\Fractal\Resource\Primitive;
use Carbon\Carbon;

class CommentTransformer extends TransformerAbstract
{
    use TraitTransformer;

    /**
     * List of available resources to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'owner',
        'post',
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Comment $comment)
    {
        return [
            'id' => $comment->id,
            'hashId' => $comment->hash_id,
            'content' => $comment->content,
            'createdAt' => $comment->created_at ? Carbon::parse($comment->created_at)->format('d/m/Y H:i:s') : null,
            'updatedAt' => $comment->updated_at ? Carbon::parse($comment->updated_at)->format('d/m/Y H:i:s') : null,
            'deletedAt' => $comment->deleted_at ? Carbon::parse($comment->deleted_at)->format('d/m/Y H:i:s') : null,
        ];
    }

    /**
     * Transform comment's owner
     *
     * @return array
     */
    public function includeOwner(Comment $comment)
    {
        return $this->loadItem($comment->owner, new UserTransformer);
    }

    /**
     * Transform comment's post
     *
     * @return array
     */
    public function includePost(Comment $comment)
    {
        return new Primitive([
            'hashId' => $comment->post->hash_id,
            'title' => $comment->post->title,
        ]);
    }
}
