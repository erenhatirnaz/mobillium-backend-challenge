<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

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
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence;

        return [
            'user_id' => User::factory(),
            'slug' => Str::slug($title) . "-" . $this->faker->randomNumber(6),
            'title' => $title,
            'content' => implode("<br/><br/>", $this->faker->paragraphs),
            'published_at' => Carbon::now(),
        ];
    }
}
