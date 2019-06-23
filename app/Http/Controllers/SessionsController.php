<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    public function __construct() {
        // 注册与登录页面访问限制 （Auth 中间件(middleware)提供的 guest 选项，用于指定一些只允许未登录用户访问的动作，只让未登录用户访问登录页面和注册页面）
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }
    // 登录表单
    public function create() {
        return view('sessions.create');
    }
    // 认证用户身份
    public function store(Request $request) {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);
        // attempt 方法会接收一个数组来作为第一个参数，该参数提供的值将用于寻找数据库中的用户数据 :bool
        if (Auth::attempt($credentials, $request->has('remember'))) {
            // 登录成功后的相关操作 登录时检查是否已激活
            if (Auth::user()->activated) {
                session()->flash('success', '欢迎回来！');
                $fallback = route('users.show', [Auth::user()]);
                // 友好的转向 intended 方法，该方法可将页面重定向到上一次请求尝试访问的页面上
                return redirect()->intended($fallback);
            } else {
                // 用户没有激活时，则视为认证失败，用户将会被重定向至首页，并显示消息提醒去引导用户查收邮件
                Auth::logout();
                session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活。');
                return redirect('/');
           }
        } else {
            // 登录失败后的相关操作
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
    }
    // 用户退出功能
    public function destroy() {
        // Laravel 默认提供的 Auth::logout() 方法来实现用户的退出功能
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }
}
