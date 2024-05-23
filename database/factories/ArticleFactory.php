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
        $continentCountryMap = [
            'Asia' => ['Afghanistan', 'China', 'India', 'Japan', 'South Korea', 'Indonesia', 'Singapore', 'Lebanon', 'Jordan', 'Oman'],
            'South America' => ['Argentina', 'Brazil', 'Chile', 'Peru', 'Bolivia'],
            'Oceania' => ['Australia', 'New Zealand', 'French Polynesia', 'Papua New Guinea'],
            'North America' => ['Canada', 'Mexico', 'United States', 'Costa Rica', 'Cuba'],
            'Europe' => ['France', 'Germany', 'United Kingdom', 'Italy', 'switzerland', 'Belgium', 'Ireland', 'Spain', 'Croatia'],
            'Africa' => ['Nigeria', 'South Africa', 'Egypt', 'Morocco', 'Senegal', 'Kenya'],
        ];

        // Select a random continent
        $continent = $this->faker->randomElement(array_keys($continentCountryMap));
        // Select a random country from the selected continent
        $country = $this->faker->randomElement($continentCountryMap[$continent]);
      
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'continent' => $continent,
            'country' =>  $country,
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
