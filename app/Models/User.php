<?php

namespace App\Models;

use App\Enums\Roles;
use App\Models\Role;
use App\Models\Vote;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function hasRole($role)
    {
        if (gettype($role) == "string") {
            $role = Roles::getEnumFromString($role);
        }

        return ($this->role === $role);
    }

    public function hasVote($articleId)
    {
        $vote = $this->votes->where('article_id', $articleId)->first();

        return ($vote) ? $vote : null;
    }
}
