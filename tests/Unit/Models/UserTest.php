<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Enums\Roles;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function testHasRoleMethodShouldReturnTrueIfUserHasAnyGivenRoles()
    {
        $user1 = User::factory()->create();
        $user1->roles()->attach(Roles::WRITER);

        $this->assertTrue($user1->hasRoles([Roles::READER, "writer"]));
    }

    public function testHasRoleMethodShouldReturnFalseIfUserHasntAnyGivenRoles()
    {
        $user1 = User::factory()->create();

        $this->assertFalse($user1->hasRoles(["reader", Roles::ADMIN]));
        $this->assertFalse($user1->hasRoles("moderator"));
    }
}
