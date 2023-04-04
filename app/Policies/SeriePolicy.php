<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Serie;
use Illuminate\Auth\Access\HandlesAuthorization;

class SeriePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can create new serie.
     *
     * @param  App\Models\User  $user
     *
     * @return boolean
     */
    public function create(User $user)
    {
        return $user->isAuthor() || $user->isAdmin();
    }

    /**
     * Determine if the given serie can be updated by the user.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Serie  $post
     *
     * @return boolean
     */
    public function modify(User $user, Serie $serie)
    {
        return $user->id == $serie->users_id;
    }
}
