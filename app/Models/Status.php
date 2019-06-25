<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// 微博模型
class Status extends Model
{
    // 定义微博模型的 fillable 属性中，允许更新微博的 content 字段
    protected $fillable = ['content'];

    // 一对多 指明一条微博属于一个用户
    public function user() {
        return $this->belongsTo(User::class);
    }
}
