<?php

namespace App\Transformers\Client;

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
            'url' => $image->getPath(),
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
