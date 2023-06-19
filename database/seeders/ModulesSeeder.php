<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class ModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name'      => 'users',
                'title'     => 'Permission Management on Users'
                
            ],
            [
                'name'      => 'groups',
                'title'     => 'Permission Management on Groups'
            ],
            [
                'name'      => 'posts',
                'title'     => 'Permission Management on Posts'
            ]
        ];

        foreach ($data as $item) {
            DB::table('modules')->insert([
                ...$item,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
