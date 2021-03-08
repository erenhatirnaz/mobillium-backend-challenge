<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Vote;
use App\Models\Article;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ArticlePageTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testShouldRenderedCorrectly()
    {
        $articles = Article::factory()->count(10)->create();

        $this->browse(function ($browser) use ($articles) {
            foreach ($articles as $article) {
                $browser->visit('/article/' . $article->slug)
                        ->assertTitleContains($article->title)
                        ->assertSeeIn('h2', $article->title)
                        ->assertSeeIn('.card-header', "Author: " . $article->user->full_name)
                        ->assertSeeIn('.card-text', $article->content)
                        ->assertSee("Rating: Not voted yet!");
            }
        });
    }

    public function testUserShouldBeAbleToVoteTheArticle()
    {
        $article = Article::factory()->create();
        $user = User::factory()->create();

        $this->browse(function ($browser) use ($article, $user) {
            $browser->loginAs($user)
                    ->visit('/article/' . $article->slug)
                    ->assertSee('Vote this article:')
                    ->value('#rating.form-control', 5)
                    ->press('Vote!')
                    ->waitForText('Your vote: 5')
                    ->assertSeeLink('Delete my vote');
        });
    }

    public function testUserShouldBeAbleToDeleteItsVoteFromTheArticle()
    {
        $article = Article::factory()->create();
        $user = User::factory()->create();
        $vote = Vote::factory()->create(['user_id' => $user->id, 'article_id' => $article->id, 'rating' => 4]);

        $this->browse(function ($browser) use ($article, $user) {
            $browser->loginAs($user)
                    ->visit('/article/' . $article->slug)
                    ->assertSee('Your vote: 4')
                    ->clickLink('Delete my vote')
                    ->waitForText('Vote this article:')
                    ->assertSee('Rating: Not voted yet!');
        });
    }
}
