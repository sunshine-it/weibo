<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

# 指定路由
Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');

// 注册 signup
Route::get('signup', 'UsersController@create')->name('signup');
// 用户资源
Route::resource('users', 'UsersController');

// 显示登录页面
Route::get('login', 'SessionsController@create')->name('login');
// 创建新会话（登录）
Route::post('login', 'SessionsController@store')->name('login');
// 销毁会话（退出登录）
Route::delete('logout', 'SessionsController@destroy')->name('logout');
// 用户的激活功能
Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');

# 用户找回密码
// 显示重置密码的邮箱发送页面
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// 邮箱发送重设链接
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// 密码更新页面
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// 执行密码更新操作
Route::post('/password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

# 微博路由
// 创建和删除只需要两个动作，因此对 resource 传参 only 键指定只生成 store, destroy 两个动作的路由
Route::resource('statuses', 'StatusesController', ['only'=> ['store', 'destroy']]);
// 关注者列表
Route::get('/users/{user}/followings', 'UsersController@followings')->name('users.followings');
// 粉丝列表
Route::get('/users/{user}/followers', 'UsersController@followers')->name('users.followers');
// 关注
Route::post('/users/followers/{user}', 'FollowersController@store')->name('followers.store');
// 取消关注
Route::delete('/users/followers/{user}', 'FollowersController@destroy')->name('followers.destroy');
