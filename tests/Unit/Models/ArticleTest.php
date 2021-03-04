<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ArticleTest extends TestCase
{
    use DatabaseMigrations;

    public function testStatusAttributeShouldReturnPublished()
    {
        $article1 = Article::factory()->make();
        $article2 = Article::factory()->make(['published_at' => Carbon::now()->subDays(4)]);

        $this->assertEquals("PUBLISHED", $article1->status);
        $this->assertEquals("PUBLISHED", $article2->status);
    }

    public function testStatusAttributeShouldReturnDraftIfPublishedAtIsNull()
    {
        $article1 = Article::factory()->draft()->make();

        $this->assertEquals("DRAFT", $article1->status);
    }

    public function testStatusAttributeShouldReturnScheduledIfPublishedAtIsFutureDateTime()
    {
        $article1 = Article::factory()->scheduled()->make();

        $this->assertEquals("SCHEDULED", $article1->status);
    }
}
