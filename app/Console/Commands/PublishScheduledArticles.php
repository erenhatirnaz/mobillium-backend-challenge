<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Article;
use Illuminate\Console\Command;

class PublishScheduledArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:scheduled-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish scheduled articles If the date of the scheduled article has arrived';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $articles = Article::skipCache()->scheduled()->get();

        if ($articles->count() == 0) {
            $this->info("[" . Carbon::now() . "] There's no scheduled articles!");
        }

        foreach ($articles as $article) {
            if ($article->published_at->lessThanOrEqualTo(Carbon::now())) {
                $article->published_at = Carbon::now();
                $article->status = ""; // for trigger the Article models' status setter
                $article->save();
                $this->info("[" . Carbon::now() . "] \"{$article->title}\" article published now!");
            }
        }
    }
}
