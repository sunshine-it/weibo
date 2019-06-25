<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use Auth;

class StaticPagesController extends Controller
{
    // home 页
    public function home() {
        // 获取用户的微博动态
        $feed_items = [];
        if (Auth::check()) {
            $feed_items = Auth::user()->feed()->paginate(10);
        }
        return view('static_pages/home',  compact('feed_items'));
    }
    // help 页
    public function help() {
        return view('static_pages/help');
    }
    // about 页
    public function about() {
        return view('static_pages/about');
    }
}
