<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\Role;
use App\Enums\Roles;

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

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRoles($roles)
    {
        if (gettype($roles) != "array") {
            $roles = [$roles];
        }

        $results = array();
        foreach ($roles as $role) {
            if (gettype($role) == "string") {
                $role = Roles::getRoleFromString($role);
            }

            if ($this->roles->find($role)) {
                array_push($results, true);
            } else {
                array_push($results, false);
            }
        }

        return !(in_array(false, $results));
    }
}
