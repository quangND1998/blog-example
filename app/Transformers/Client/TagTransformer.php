<?php

namespace App\Transformers\Client;

use League\Fractal\TransformerAbstract;
use App\Models\Tag;
use App\Transformers\TraitTransformer;
use Carbon\Carbon;

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
            'hashId' => $tag->hash_id,
            'name' => $tag->name,
            'slug' => $tag->slug,
            'description' => $tag->description ?: __('Không có mô tả'),
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
        return $this->loadItem($tag->thumbnailImage, new ImageTransformer, [
            'url' => env('DEFAULT_TAG_IMAGE_URL'),
        ]);
    }
}
