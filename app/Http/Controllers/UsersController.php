<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Mail;

class UsersController extends Controller
{
    // 构造器方法，当一个类对象被创建之前该方法将会被调用
    public function __construct() {
        // 中间件(middleware)
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
        ]);
        // 只让未登录用户访问注册页面
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    // 用户列表
    public function index() {
        // 列出所有用户
        $users = User::paginate(10);
        return view('users.index', compact('users'));
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
        // 激活邮箱的发送操作
        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');
        // // 注册后自动登录
        // Auth::login($user);
        // // 用户注册成功后，在页面顶部位置显示注册成功的提示信息
        // session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        // // 重定向到其个人页面
        // return redirect()->route('users.show', [$user]);
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

    // 删除用户的动作
    public function destroy(User $user) {
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }

    // 激活邮箱的发送操作
    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        // 已经使用线上 QQ 邮箱
        //$from = env('SEEDER_ONE_USER_EMAIL') ? env('SEEDER_ONE_USER_EMAIL') : 'allen@163.com';
        //$name = env('SEEDER_ONE_USER_NAME') ? env('SEEDER_ONE_USER_NAME') : 'Allen';
        $to = $user->email;
        $subject = "感谢注册 Weibo 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    // 激活功能
    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }
}
