<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name'=>"Tips",
            ],
            [
                'name'=>"Culture",
            ],
            [
                'name'=>"Sport",
            ],
            [
                'name'=>"Family",
            ],
            [
                'name'=>"Food",
            ],
        ];

        //Insert data in the table
        foreach ($categories as $data) {
            DB::table('categories')->insert([
                'name' => $data['name'],
            ]);
        }
    }
}
