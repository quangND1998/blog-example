<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Transformers\Admin\UserTransformer;
use App\Repositories\UserRepository;
use App\Http\Requests\Users\UpdateUserRoleRequest;
use App\Models\User;

class UserController extends ApiController
{
    protected $user;
    protected $allowFilter = [
        'role',
        'email',
        'socialite',
    ];

    /**
     * Constructor
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->user = $userRepository;
    }

    /**
     * Get user list
     *
     * @var Illuminate\Http\Request $request
     *
     * @return json
     */
    public function index(Request $request)
    {
        $condition = $this->constructFilter($request);
        $users = $this->user->getListByCondition($condition);

        return $this->response()
            ->attach(
                $users,
                new UserTransformer,
                ['postCount', 'imageCount', 'commentCount', 'voteCount', 'serieCount']
            )
            ->success();
    }

    /**
     * Update user's role
     *
     * @var App\Models\User $user
     * @var App\Http\Requests\Users\UpdateUserRoleRequest $request
     *
     * @return json
     */
    public function update(User $user, UpdateUserRoleRequest $request)
    {
        $result = $user->update(['role' => $request->input('role')]);
        if ($result) {
            return $this->response()->success(__('Cập nhật quyền thành công'));
        }

        return $this->response()->fail(__('Có lỗi xảy ra, vui lòng thử lại'));
    }

    /**
     * Construct filter condition from request
     *
     * @var Illuminate\Http\Request $request
     *
     * @return array
     */
    private function constructFilter(Request $request)
    {
        $condition = [];
        $filter = $request->only($this->allowFilter);
        if (isset($filter['email'])) {
            array_push($condition, ['email', 'like', '%' . $filter['email'] . '%']);
        }
        if (isset($filter['role'])) {
            array_push($condition, ['role', '=', $filter['role']]);
        }
        if (isset($filter['socialite'])) {
            array_push($condition, ['socialite_provider', '=', $filter['socialite']]);
        }

        return $condition;
    }
}
