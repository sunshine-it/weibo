<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Status;
use Faker\Factory as Factory;

// 初始化 Faker\Factory 使用中文
$faker = Factory::create('zh_CN');
// 微博发布表单模型工厂
$factory->define(Status::class, function () use ($faker) {
    // 生成随机日期 生成随机时间
    $date_time = $faker->date . ' ' . $faker->time;
    // dump(['zh_CN'=> $faker->catchPhrase]); // 来自 fzaninotto/Faker/blob/master/src/Faker/Provider/zh_CN/Company.php
    return [
        'content'    => $faker->catchPhrase,
        'created_at' => $date_time,
        'updated_at' => $date_time,
    ];
});
