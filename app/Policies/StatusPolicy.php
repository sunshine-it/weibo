<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Status;

// 微博授权策略
class StatusPolicy
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

    // 定义微博删除动作相关的授权
    public function destroy(User $user, Status $status) {
        return $user->id === $status->user_id;
    }
}
