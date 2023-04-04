<?php

namespace App\Transformers\Admin;

use League\Fractal\TransformerAbstract;
use App\Transformers\TraitTransformer;
use App\Models\Image;

class ImageTransformer extends TransformerAbstract
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
    public function transform(Image $image)
    {
        return [
            'id' => $image->id,
            'uuid' => $image->uuid,
            'name' => $image->origin_name,
            'url' => $image->getPath(),
            'type' => strtoupper($image->type),
            'createdAt' => $image->created_at,
            'updatedAt' => $image->updated_at,
        ];
    }

    /**
     * Transform image's owner
     *
     * @return array
     */
    public function includeOwner(Image $image)
    {
        return $this->loadItem($image->owner, new UserTransformer);
    }
}
