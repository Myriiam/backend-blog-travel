<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;

class DatabaseSeeder extends Seeder
{   
    use WithoutModelEvents;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            CategorySeeder::class,
        ]);

       // Create users
       User::factory()->count(10)->create();

       // Create articles with relationships
       Article::factory()->count(10)->create();
    }
}
