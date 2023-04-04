<?php

namespace App\Transformers\Admin;

use League\Fractal\TransformerAbstract;
use App\Transformers\TraitTransformer;
use App\Models\Post;
use Carbon\Carbon;

class PostTransformer extends TransformerAbstract
{
    use TraitTransformer;

    /**
     * List of available resources to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'owner',
        'coverImage',
        'serie',
        'tags',
        'votes',
        'commentCount',
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Post $post)
    {
        return [
            'id' => $post->id,
            'hashId' => $post->hash_id,
            'title' => $post->title,
            'slug' => $post->slug,
            'preview' => $post->preview,
            'content' => $post->content,
            'status' => $post->status,
            'viewCount' => $post->view_count,
            'shareCount' => $post->share_count,
            'publishAt' => $post->publish_at ? Carbon::parse($post->publish_at)->format('d/m/Y H:i:s') : null,
            'createdAt' => $post->created_at ? Carbon::parse($post->created_at)->format('d/m/Y H:i:s') : null,
            'updatedAt' => $post->updated_at ? Carbon::parse($post->updated_at)->format('d/m/Y H:i:s') : null,
            'deletedAt' => $post->deleted_at ? Carbon::parse($post->deleted_at)->format('d/m/Y H:i:s') : null,
        ];
    }

    /**
     * Transform post's owner
     *
     * @return array
     */
    public function includeOwner(Post $post)
    {
        return $this->loadItem($post->owner, new UserTransformer);
    }

    /**
     * Transform post's owner
     *
     * @return array
     */
    public function includeCoverImage(Post $post)
    {
        return $this->loadItem($post->coverImage, new ImageTransformer);
    }

    /**
     * Transform post's serie
     *
     * @return array
     */
    public function includeSerie(Post $post)
    {
        return $this->loadItem($post->serie, new SerieTransformer);
    }

    /**
     * Transform post's tags
     *
     * @return array
     */
    public function includeTags(Post $post)
    {
        return $this->loadCollection($post->tags, new TagTransformer);
    }

    /**
     * Transfrom post's votes total
     */
    public function includeVotes(Post $post)
    {
        return $this->loadItem(null, new ImageTransformer, $post->votes->sum('value'));
    }

    /**
     * Transfrom post's comment count
     */
    public function includeCommentCount(Post $post)
    {
        return $this->loadItem(null, new ImageTransformer, $post->comments->count());
    }
}
