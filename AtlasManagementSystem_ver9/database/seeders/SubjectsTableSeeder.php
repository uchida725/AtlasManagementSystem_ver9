<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// メソッドでDBを使う場合は、USE宣言しないと使えないから入れる！

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 新規登録時に生徒を選択した場合、国語、数学、英語を追加できるようにする設定。
        DB::table('subjects')->insert([
            ['id' => 1, 'subject' => '国語'],
            ['id' => 2, 'subject' => '数学'],
            ['id' => 3, 'subject' => '英語'],
        ]);
//     DB::table('subjects') は、subjects テーブルを操作する。
// 　　insert([...]) で、複数のレコードを一気に追加できる。
// 　　['subject' => '国語'] みたいに、カラム名と値をセットで書く。
// nameのsubjectはマイグレーションのsubjectsテーブルで、教科の部分のnameがsubjectで定義されているため！
    }
}
