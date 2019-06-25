<?php

use Illuminate\Database\Seeder;
use App\Models\Status;
// use Faker\Factory as Factory;

// 用户生成N条微博假数据 seeder
class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 只为前三个用户生成共 100 条微博假数据。
        $user_ids = ['1','2','3'];
        // 通过 app() 方法来获取一个 Faker 容器 的实例
        // $faker = app(Faker\Generator::class);
        $faker = Faker\Factory::create('zh_CN');
        // 借助 randomElement 方法来取出用户 id 数组中的任意一个元素并赋值给微博的 user_id，使得每个用户都拥有不同数量的微博
        $statuses = factory(Status::class)
                    ->times(100)
                    ->make()
                    ->each(function ($status) use ($faker, $user_ids) {
                $status->user_id = $faker->randomElement($user_ids);
        });
        // dump($statuses->toArray()); // 查看对应存储数据
        Status::insert($statuses->toArray());
    }
}
