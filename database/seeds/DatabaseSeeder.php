<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        // 指定调用用户数据填充文件
        $this->call(UsersTableSeeder::class);
        // 指定调用微博数据填充文件
        $this->call(StatusesTableSeeder::class);
        Model::reguard();
    }
}
