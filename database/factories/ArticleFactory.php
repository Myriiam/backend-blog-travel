<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Article;
use App\Models\User;
use App\Models\Image;
use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Category;
//use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{   
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
       // $faker = Faker::create();

        return [
            'user_id' => User::factory(), // This will create a user if one doesn't exist
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'continent' => $this->faker->randomElement(['Africa', 'Asia', 'Europe', 'North America', 'South America', 'Australia']),
            'country' => $this->faker->country,
            'image_url' => $this->faker->imageUrl(),
            'image_public_id' => $this->faker->uuid,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Article $article) {
            $categories = Category::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $article->categories()->attach($categories);

            Image::factory()->count(rand(1, 3))->create(['article_id' => $article->id]);
            Comment::factory()->count(rand(1, 5))->create(['article_id' => $article->id]);
            Favorite::factory()->count(rand(1, 5))->create(['article_id' => $article->id]);
        });
    }
}
