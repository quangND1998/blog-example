<?php

namespace App\Transformers\Admin;

use League\Fractal\TransformerAbstract;
use App\Transformers\TraitTransformer;
use App\Models\Tag;

class TagTransformer extends TransformerAbstract
{
    use TraitTransformer;

    /**
     * List of available resources to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'thumbnailImage',
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Tag $tag)
    {
        return [
            'id' => $tag->id,
            'hashId' => $tag->hash_id,
            'name' => $tag->name,
            'slug' => $tag->slug,
            'description' => $tag->description ?: __('Không có mô tả'),
            'createdAt' => $tag->created_at,
            'updatedAt' => $tag->updated_at,
            'totalPost' => $tag->posts_count ?: $tag->posts()->count(),
        ];
    }

    /**
     * Transform tag's image
     *
     * @return array
     */
    public function includeThumbnailImage(Tag $tag)
    {
        return $this->loadItem($tag->thumbnailImage, new ImageTransformer);
    }
}
