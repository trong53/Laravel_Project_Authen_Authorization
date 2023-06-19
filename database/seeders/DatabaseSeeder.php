<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use App\Models\{User, Group, Post};
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');  // Disable foreign key checks!
        $groupId = Group::insertGetId([              // Insert cua query builder, nen ko support created_at, updated_at
            'name'      => 'Administration',
            'user_id'   => 1,
            'created_at'=> date('Y-m-d H-i-s'),
            'updated_at'=> date('Y-m-d H-i-s')
        ]);
        // dd($groupId);       // 5 // database\seeders\DatabaseSeeder.php:23
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');  // Enable foreign key checks!

        if ($groupId > 0) {
            $userId = User::insertGetId([
                'name'  => 'Home',
                'email' => 'orchide5381@gmail.com',
                'password'  => Hash::make('123456'),
                'group_id'  => $groupId,
                'created_at'=> date('Y-m-d H-i-s'),
                'updated_at'=> date('Y-m-d H-i-s')
            ]);

            if ($userId > 0) {
                for ($i = 1; $i <= 5; $i++) {
                    Post::create([                      // create cua Eloquent nen support created_at va updated_at
                        'title'  => fake()->text(40),
                        'content' => fake()->text(200),
                        'user_id'  => $userId
                    ]);
                }
            }
        }
    }
}
