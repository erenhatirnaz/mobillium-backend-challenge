<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'content',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusAttribute()
    {
        if (!$this->published_at) {
            return "DRAFT";
        }

        if ($this->published_at->lessThanOrEqualTo(Carbon::now())) {
            return "PUBLISHED";
        } else {
            return "SCHEDULED";
        }
    }

    public function getContentSummaryAttribute()
    {
        return Str::of($this->content)->explode('<br/><br/>')[0];
    }

    public function getLinkAttribute()
    {
        return route('article.show', ['slug' => $this->slug]);
    }

    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', Carbon::now());
    }
}
