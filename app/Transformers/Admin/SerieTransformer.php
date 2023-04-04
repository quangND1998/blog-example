<?php

namespace App\Transformers\Admin;

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
            'id' => $serie->id,
            'hashId' => $serie->hash_id,
            'name' => $serie->name,
            'slug' => $serie->slug,
            'description' => $serie->description,
            'createdAt' => $serie->created_at ? Carbon::parse($serie->created_at)->format('d/m/Y H:i:s') : null,
            'updatedAt' => $serie->updated_at ? Carbon::parse($serie->created_at)->format('d/m/Y H:i:s') : null,
            'deletedAt' => $serie->deleted_at ? Carbon::parse($serie->created_at)->format('d/m/Y H:i:s') : null,
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
