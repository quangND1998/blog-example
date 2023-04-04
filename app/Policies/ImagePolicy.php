<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ImagePolicy
{
    use HandlesAuthorization;

    /**
     * Grant all actions for admin
     *
     * @var App\Models\User $user
     * @var string $ability
     *
     * @return boolean
     */
    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    /**
     * Determine if the user can create new serie.
     *
     * @param  App\Models\User  $user
     *
     * @return boolean
     */
    public function create(User $user)
    {
        return $user->isAuthor();
    }
}
