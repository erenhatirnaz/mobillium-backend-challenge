<?php

namespace Database\Factories;

use App\Models\Vote;
use App\Models\User;
use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'article_id' => Article::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
        ];
    }
}
