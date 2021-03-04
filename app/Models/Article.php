<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
}
