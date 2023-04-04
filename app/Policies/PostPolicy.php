<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
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
        return $user->isAdmin() || $user->isAuthor();
    }

    /**
     * Determine if the user can create new serie.
     *
     * @param  App\Models\User  $user
     *
     * @return boolean
     */
    public function modify(User $user, Post $post)
    {
        return $user->id == $post->users_id;
    }
}
