<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Register your seeders here
        $this->call([
            // 作成したSeederクラスをcall()メソッドに渡す
            UsersTableSeeder::class,
            SubjectsTableSeeder::class,

        ]);
    }
}
