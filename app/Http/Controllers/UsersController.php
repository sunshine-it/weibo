<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class UsersController extends Controller
{
    // 构造器方法，当一个类对象被创建之前该方法将会被调用
    public function __construct() {
        // 中间件(middleware)
        $this->middleware('auth', [
            'except' => ['show', 'create', 'update']
        ]);
        // 只让未登录用户访问注册页面
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }
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
        // 将用户提交的信息存储到数据库
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        // 注册后自动登录
        Auth::login($user);
        // 用户注册成功后，在页面顶部位置显示注册成功的提示信息
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        // 重定向到其个人页面
        return redirect()->route('users.show', [$user]);
    }

    // 编辑表单
    public function edit(User $user) {
        // 使用 authorize 方法来验证用户授权策略
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    // 更新到数据
    public function update(User $user, Request $request) {
        // 使用 authorize 方法来验证用户授权策略
        $this->authorize('update', $user);
        // 数据验证
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);
        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);
        session()->flash('success', '个人资料更新成功！');
        return redirect()->route('users.show', $user->id);
    }
}
