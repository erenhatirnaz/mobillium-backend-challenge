<?php

namespace Tests\Unit\Models;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Vote;
use App\Models\Article;
use App\Enums\ArticleStatus;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ArticleTest extends TestCase
{
    use DatabaseMigrations;

    public function testStatusAttributeShouldReturnPublished()
    {
        $article1 = Article::factory()->make();
        $article2 = Article::factory()->make(['published_at' => Carbon::now()->subDays(4)]);

        $this->assertEquals(ArticleStatus::PUBLISHED, $article1->status);
        $this->assertEquals(ArticleStatus::PUBLISHED, $article2->status);
    }

    public function testStatusAttributeShouldReturnDraftIfPublishedAtIsNull()
    {
        $article1 = Article::factory()->draft()->make();

        $this->assertEquals(ArticleStatus::DRAFT, $article1->status);
    }

    public function testStatusAttributeShouldReturnScheduledIfPublishedAtIsFutureDateTime()
    {
        $article1 = Article::factory()->scheduled()->make();

        $this->assertEquals(ArticleStatus::SCHEDULED, $article1->status);
    }

    public function testAverageRatingAttributeShouldCalculateRatingCorrectly()
    {
        $article1 = Article::factory()->create();
        $lessEfectiveVotes = Vote::factory()->count(7)->create(['article_id' => $article1->id, 'rating' => 5]);
        sleep(1);
        $moreEfectiveVote1 = Vote::factory()->create(['article_id' => $article1->id, 'rating' => 3]);
        $moreEfectiveVote2 = Vote::factory()->create(['article_id' => $article1->id, 'rating' => 1]);
        $moreEfectiveVote3 = Vote::factory()->create(['article_id' => $article1->id, 'rating' => 4]);

        // my calculation = (((3+1+4)*2) + (5*7)) / (10+3) = 3.92307692308 =~ 3.9
        //                    \___1___/    \_2_/    \_3_/
        // 1=moreEffectiveVotesTotal
        // 2=lessEffectiveVotesTotal
        // 3=totalVoteCount + percent30

        $this->assertEquals(3.9, $article1->average_rating);
    }

    public function testAverageRatingAttributeShouldReturnNotVotedYetMessageIfTheArticleHasntAnyVote()
    {
        $article2 = Article::factory()->create();

        $this->assertEquals("Not voted yet!", $article2->average_rating);
    }
}
