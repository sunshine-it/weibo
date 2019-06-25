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

    // 只有当前登录用户为管理员才能执行删除操作并且删除的用户对象不是自己（即使是管理员也不能自己删自己）
    public function destroy(User $currentUser, User $user) {
        // 只有当前用户拥有管理员权限且删除的用户不是自己时才显示链接
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }

    // 自己不能关注自己
    public function follow(User $currentUser, User $user) {
        return $currentUser->id !== $user->id;
    }
}
