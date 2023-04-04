<?php

namespace App\Transformers;

use League\Fractal\Resource\Primitive;

trait TraitTransformer
{
    protected function loadCollection($relation, $transfomer)
    {
        if ($relation) {
            return $this->collection($relation, $transfomer);
        } else {
            return [];
        }
    }

    protected function loadItem($relation, $transfomer, $defaultResult = null)
    {
        if ($relation) {
            return $this->item($relation, $transfomer);
        } else {
            return new Primitive($defaultResult);
        }
    }
}
