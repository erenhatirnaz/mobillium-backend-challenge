<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Str;
use App\Enums\ArticleStatus;
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
            'content' => implode(" ", $this->faker->paragraphs),
            'published_at' => Carbon::now(),
            'status' => ArticleStatus::PUBLISHED,
        ];
    }

    public function draft()
    {
        return $this->state(function ($attributes) {
            return [
                'published_at' => null,
                'status' => ArticleStatus::DRAFT,
            ];
        });
    }

    public function scheduled()
    {
        return $this->state(function ($attributes) {
            return [
                'published_at' => Carbon::now()->addDays(4),
                'status' => ArticleStatus::SCHEDULED,
            ];
        });
    }
}
