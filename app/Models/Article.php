<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Str;
use App\Enums\ArticleStatus;
use Illuminate\Database\Eloquent\Model;
use Whtht\PerfectlyCache\Traits\PerfectlyCachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory, PerfectlyCachable;

    protected $fillable = [
        'slug',
        'title',
        'content',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $with = [
        'votes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class)->orderBy('created_at', 'DESC');
    }

    public function getContentSummaryAttribute()
    {
        return Str::of($this->content)->explode('.')->take(4)->join('.') . " >>>";
    }

    public function getLinkAttribute()
    {
        return route('article.show', ['slug' => $this->slug]);
    }

    public function getNextArticleLinkAttribute()
    {
        $nextArticle = self::without(['votes', 'user'])->where('status', ArticleStatus::PUBLISHED)
                                                       ->where('published_at', '>', $this->published_at)
                                                       ->orderBy('published_At')->get()->first();

        return ($nextArticle) ? $nextArticle->link : null;
    }

    public function getPreviousArticleLinkAttribute()
    {
        $previousArticle = self::without(['votes', 'user'])->where('status', ArticleStatus::PUBLISHED)
                                                           ->where('published_at', '<', $this->published_at)
                                                           ->orderBy('published_At', 'DESC')->get()->first();

        return ($previousArticle) ? $previousArticle->link : null;
    }

    public function getAverageRatingAttribute()
    {
        $votes = $this->votes;

        $totalVoteCount = $votes->count();
        if ($totalVoteCount == 0) {
            return 'Not voted yet!';
        }

        $percent30 = round($totalVoteCount * 0.3);
        $percent70 = $totalVoteCount - $percent30;

        $moreEffectiveVotesTotal = $votes->take($percent30)->pluck('rating')->sum() * 2;
        $lessEffectiveVotesTotal = $votes->take($percent70 * -1)->pluck('rating')->sum();

        $total = ($moreEffectiveVotesTotal + $lessEffectiveVotesTotal) / ($totalVoteCount + $percent30);

        return round($total, 1);
    }

    public function setStatusAttribute($value)
    {
        if (!$this->published_at) {
            $this->attributes["status"] = ArticleStatus::DRAFT;
            return;
        }

        if ($this->published_at->greaterThan(Carbon::now())) {
            $this->attributes["status"] = ArticleStatus::SCHEDULED;
        } elseif ($this->published_at->lessThanOrEqualTo(Carbon::now())) {
            $this->attributes["status"] = ArticleStatus::PUBLISHED;
        }
    }

    public function scopePublished($query)
    {
        return $query->where('status', ArticleStatus::PUBLISHED)
                     ->orderBy('published_at', 'DESC');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', ArticleStatus::SCHEDULED);
    }
}
