<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
// 用户模型
class User extends Authenticatable
{
    use Notifiable;

    // 对应的用户表 来指明要进行数据库交互的数据库表名称
    protected $table = 'users';

    /**
     * fillable 在过滤用户提交的字段，只有包含在该属性中的字段才能够被正常更新
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     *  对用户密码或其它敏感信息在用户实例通过数组或 JSON 显示时进行隐藏
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // creating 用于监听模型被创建之前的事件
    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->activation_token = Str::random(10);
        });
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 定义一个 gravatar 方法，用来生成用户的头像 (格万特)
    public function gravatar($size = 100) {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    // 一个用户拥有多条微博
    public function statuses() {
        return $this->hasMany(Status::class);
    }

    // 动态流原型
    public function feed() {
        // 根据创建时间来倒序排序
        return $this->statuses()->orderBy('created_at', 'desc');
    }

    // 获取粉丝关系列表
    public function followers() {
        // 多对多关联
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    // 获取用户关注人列表
    public function followings() {
        // 多对多关联
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    // 定义关注
    public function follow($user_ids) {
        if ( ! is_array($user_ids)) {
            $user_ids = compact('user_ids');
            $this->followings()->sync($user_ids, false);
        }
    }

    // 定义取消关注
    public function unfollow($user_ids) {
        if ( ! is_array($user_ids)) {
            $user_ids = compact('user_ids');
            $this->followings()->detach($user_ids, false);
        }
    }

    // 判断当前登录的用户 A 是否关注了用户 B
    public function isFollowing($user_id) {
        return $this->followings->contains($user_id);
    }
}
