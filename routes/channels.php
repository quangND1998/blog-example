<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('receive-feedback', function ($user) {
    return $user->isAdmin();
});

Broadcast::channel('user-{userId}', function ($user, $userId) {
    return $user->id === $userId;
});
