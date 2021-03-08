<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use App\Models\Article;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HomepageTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testShouldRenderedCorrectly()
    {
        $this->browse(function ($browser) {
            $browser->visit(new HomePage())
                    ->assertSee('Mobillium Back-End Challange')
                    ->assertSeeLink('Login')
                    ->assertSeeLink('Register');
        });
    }

    public function testShouldListedAllPublishedArticles()
    {
        $articles = Article::factory()->count(5)->create();

        $this->browse(function ($browser) use ($articles) {
            foreach ($articles as $article) {
                $browser->visit(new HomePage())
                        ->assertSeeLink($article->title)
                        ->assertSee($article->content_summary);
            }
        });
    }

    public function testShouldNotListedDraftOrScheduledArticles()
    {
        $draftArticles = Article::factory()->draft()->count(3)->create();
        $scheduledArticles = Article::factory()->scheduled()->count(3)->create();

        $this->browse(function ($browser) use ($draftArticles, $scheduledArticles) {
            foreach ($draftArticles as $draftArticle) {
                $browser->visit(new HomePage())
                        ->assertDontSeeLink($draftArticle->title)
                        ->assertDontSee($draftArticle->content_summary);
            }
            foreach ($scheduledArticles as $scheduledArticle) {
                $browser->visit(new HomePage())
                        ->assertDontSeeLink($scheduledArticle->title)
                        ->assertDontSee($scheduledArticle->content_summary);
            }
        });
    }
}
