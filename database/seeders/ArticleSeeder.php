<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Article::factory()->count(3)->create();
        sleep(5);
        Article::factory()->count(3)->create();
        sleep(3);
        Article::factory()->count(4)->create();
        Article::factory()->draft()->count(5)->create();
        Article::factory()->scheduled()->count(5)->create();
    }
}
