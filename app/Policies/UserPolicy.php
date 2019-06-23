<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
// 用户授权策略类
class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // 用户只能编辑自己的资料 生成的用户授权策略添加 update 方法，用于用户更新时的权限验证
    public function update(User $currentUser, User $user) {
        // dump($currentUser->id); // 当前登录的用户ID
        // dump($user->id); // 当前修改的用户ID
        return $currentUser->id === $user->id;
    }
}
