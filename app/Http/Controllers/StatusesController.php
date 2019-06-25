<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use Auth;
// 微博控制器
class StatusesController extends Controller
{
    // 中间件
    public function __construct() {
        $this->middleware('auth');
    }

    // 创建微博评论
    public function store(Request $request) {
        // 校验规则
        $this->validate($request, [
            'content' => 'required|max:140'
        ]);
        // 用户实例及关联数据存储
        Auth::user()->statuses()->create([
            'content' => $request['content']
        ]);
        // 提示信息
        session()->flash('success', '发布成功！');
        return redirect()->back();
    }
}
