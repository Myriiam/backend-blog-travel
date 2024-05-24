<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Article;
use App\Models\Image;
//use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{   
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Image::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        $imagesUrls = [
            'https://res.cloudinary.com/drjjwnstk/image/upload/v1716409532/article-images/oihythfj16peiqudzp6j.jpg',
            'https://res.cloudinary.com/drjjwnstk/image/upload/v1716383814/article-images/xdnon5zolioouoyz8qqi.jpg',
            'https://res.cloudinary.com/drjjwnstk/image/upload/v1716383812/article-images/cgqrxzsxg2lxiahztv62.jpg',
        ];

        // Select a random image URL from the array
        $imagesUrl = $this->faker->randomElement($imagesUrls);


        return [
            'article_id' => Article::factory(), // Ensures that an article is created if none exists
            'image_url' => $imagesUrl,
            'image_public_id' => $this->faker->uuid,
        ];
    }
}
