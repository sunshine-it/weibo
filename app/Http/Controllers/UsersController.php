<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class UsersController extends Controller
{
    // 注册
    public function create() {
        return view('users.create');
    }

    // 显示用户个人信息的页面
    public function show(User $user) {
        return view('users.show', compact('user'));
    }

    // 处理表单数据提交后的 store 方法，用于处理用户创建的相关逻辑
    public function store(Request $request) {
        // validate 方法来进行数据验证 第一个参数为用户的输入数据，第二个参数为该输入数据的验证规则
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);
        return;
    }
}
