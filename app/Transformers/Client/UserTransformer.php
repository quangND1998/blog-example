<?php

namespace App\Transformers\Client;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'username' => $user->username,
            'email' => $user->email,
            'avatar' => $user->avatar,
        ];
    }
}
