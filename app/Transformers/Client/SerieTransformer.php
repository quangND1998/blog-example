<?php

namespace App\Transformers\Client;

use League\Fractal\TransformerAbstract;
use App\Transformers\TraitTransformer;
use App\Models\Serie;
use Carbon\Carbon;

class SerieTransformer extends TransformerAbstract
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
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Serie $serie)
    {
        return [
            'hashId' => $serie->hash_id,
            'name' => $serie->name,
            'slug' => $serie->slug,
            'description' => $serie->description,
            'updatedAt' => $serie->updated_at ? Carbon::parse($serie->updated_at)->format('H:i:s d/m/Y') : null,
            'totalPost' => $serie->posts_count ?: $serie->posts()->count(),
        ];
    }

    /**
     * Transform serie's owner
     *
     * @return array
     */
    public function includeOwner(Serie $serie)
    {
        return $this->loadItem($serie->owner, new UserTransformer);
    }

    /**
     * Transform serie's owner
     *
     * @return array
     */
    public function includeCoverImage(Serie $serie)
    {
        return $this->loadItem($serie->coverImage, new ImageTransformer);
    }
}
