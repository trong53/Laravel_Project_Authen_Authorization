<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Post;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 20; $i++) {
            Post::create([
                'title'  => fake()->text(40),
                'content' => fake()->text(200),
                'user_id'  => random_int(1, 7)
            ]);
        }
    }
}
