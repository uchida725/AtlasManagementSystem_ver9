<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// メソッドでDBを使う場合は、USE宣言しないと使えないから入れる！

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 初期データの設定。ここに入れてdb:seedをコマンドで実行すると、データ登録ができる。
        DB::table('users')->insert([
            [
            'over_name' => '山田',
            'under_name' => '太郎',
            'over_name_kana' => 'ヤマダ',
            'under_name_kana' => 'タロウ',
            'mail_address' => 'taro@gmail.com',
            'sex' => '2',
            'birth_day' => '2025-01-01',
            'role' => '3',
            'password' => bcrypt('taro555')
            ]
         ]);
        //  ※メールアドレスはユニークがついていて被らないようになっているため、重複できないから注意

    }
}
