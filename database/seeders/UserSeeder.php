<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 5; $i++) {
            $group = Group::find(2);
            $group->users()->create([
                'name'  => fake()->name,
                'email' => fake()->email,
                'password'  => Hash::make('123456'),
            ]);
        }
    }
}
