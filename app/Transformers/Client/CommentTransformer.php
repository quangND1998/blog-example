<?php

namespace App\Transformers\Client;

use League\Fractal\TransformerAbstract;
use App\Transformers\TraitTransformer;
use App\Models\Comment;
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
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Comment $comment)
    {
        return [
            'id' => $comment->hash_id,
            'content' => $comment->content,
            'createdAt' => $comment->created_at ? Carbon::parse($comment->created_at)->format('H:i:s d/m/Y') : null,
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
}
