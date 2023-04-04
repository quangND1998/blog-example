<?php

namespace App\Transformers\Admin;

use League\Fractal\TransformerAbstract;
use App\Transformers\TraitTransformer;
use App\Models\User;
use League\Fractal\Resource\Primitive;
use Carbon\Carbon;

class UserTransformer extends TransformerAbstract
{
    use TraitTransformer;

    /**
     * List of available resources to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'postCount',
        'imageCount',
        'commentCount',
        'voteCount',
        'serieCount',
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'hashId' => $user->hash_id,
            'socialiteId' => $user->socialite_id,
            'socialiteProvider' => $user->socialite_provider,
            'username' => $user->username,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'role' => $user->role,
            'createdAt' => $user->created_at ? Carbon::parse($user->created_at)->format('d/m/Y H:i:s') : null,
            'updatedAt' => $user->updated_at ? Carbon::parse($user->updated_at)->format('d/m/Y H:i:s') : null,
            'deletedAt' => $user->deleted_at ? Carbon::parse($user->deleted_at)->format('d/m/Y H:i:s') : null,
        ];
    }

    /**
     * Transform tag's post count
     *
     * @return array
     */
    public function includePostCount(User $user)
    {
        return new Primitive($user->posts_count);
    }

    /**
     * Transform user's image count
     *
     * @return array
     */
    public function includeImageCount(User $user)
    {
        return new Primitive($user->images_count);
    }

    /**
     * Transform user's comment count
     *
     * @return array
     */
    public function includeCommentCount(User $user)
    {
        return new Primitive($user->comments_count);
    }

    /**
     * Transform user's clap count
     *
     * @return array
     */
    public function includeVoteCount(User $user)
    {
        return $this->loadItem(null, new ImageTransformer, $user->votes()->count());
    }

    /**
     * Transform user's serie count
     *
     * @return array
     */
    public function includeSerieCount(User $user)
    {
        return new Primitive($user->series_count);
    }
}
