<?php

namespace App\Transformers\Client;

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
            'hashId' => $post->hash_id,
            'title' => $post->title,
            'slug' => $post->slug,
            'preview' => $post->preview,
            'content' => $post->content,
            'viewCount' => $post->view_count,
            'shareCount' => $post->share_count,
            'publishAt' => $post->publish_at ? Carbon::parse($post->publish_at)->format('H:i:s d/m/Y') : null,
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
