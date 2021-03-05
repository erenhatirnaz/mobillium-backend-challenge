<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Enums\Roles;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function testHasRoleMethodShouldReturnTrueIfUserHasGivenRole()
    {
        $reader1 = User::factory()->create();
        $writer1 = User::factory()->writer()->create();
        $moderator1 = User::factory()->moderator()->create();
        $admin1 = User::factory()->admin()->create();

        $this->assertTrue($reader1->hasRole(Roles::READER));
        $this->assertTrue($writer1->hasRole("writer"));
        $this->assertTrue($moderator1->hasRole(Roles::MODERATOR));
        $this->assertTrue($admin1->hasRole("admin"));
    }

    public function testHasRoleMethodShouldReturnFalseIfUserHasntGivenRole()
    {
        $reader1 = User::factory()->create();

        $this->assertFalse($reader1->hasRole(Roles::WRITER));
        $this->assertFalse($reader1->hasRole("admin"));
    }
}
