<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Transformers\Client\PersonalTransformer;
use App\Http\Requests\Users\UpdateUserInfoRequest;

class UserController extends ApiController
{
    /**
     * Get my personal info
     *
     * @return json
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        if ($user) {
            return $this->response()
                ->attach($user, new PersonalTransformer)
                ->success();
        } else {
            return response()->json([
                'data' => null,
            ]);
        }
    }

    /**
     * Update user's username
     *
     * @var App\Http\Requests\Users\UpdateUserInfoRequest $request
     *
     * @return json
     */
    public function update(UpdateUserInfoRequest $request)
    {
        auth()->user()->update([
            'username' => $request->input('username'),
        ]);
        $user = auth()->user();

        return $this->response()
            ->attach($user, new PersonalTransformer)
            ->success();
    }

    /**
     * Logout current user
     *
     * @return json
     */
    public function logout()
    {
        auth()->logout();

        return $this->response()
            ->success();
    }
}
