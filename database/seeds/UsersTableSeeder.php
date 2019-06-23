<?php

use Illuminate\Database\Seeder;
use App\Models\User;
// 用户相关的假数据
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory 这个辅助函数来生成一个使用假数据的用户对象，创建 50 个假用户
        // times 和 make 方法是由 FactoryBuilder 类 提供的 API。
        // times 接受一个参数用于指定要创建的模型数量，make 方法调用后将为模型创建一个 集合。
        $users = factory(User::class)->times(50)->make();
        User::insert($users->makeVisible(['password', 'remember_token'])->toArray());
        $user = User::find(1);
        $user->name = env('SEEDER_ONE_USER_NAME') ? env('SEEDER_ONE_USER_NAME') : 'Allen';
        $user->email = env('SEEDER_ONE_USER_EMAIL') ? env('SEEDER_ONE_USER_EMAIL') : 'allen@163.com';
        $user->is_admin = true;
        $user->save();
    }
}
