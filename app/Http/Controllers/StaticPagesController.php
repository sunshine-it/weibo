<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPagesController extends Controller
{
    // home 页
    public function home() {
        return view('static_pages/home');
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
