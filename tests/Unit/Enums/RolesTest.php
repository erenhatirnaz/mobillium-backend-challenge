<?php

namespace Tests\Unit\Enums;

use App\Models\Role;
use App\Enums\Roles;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RolesTest extends TestCase
{
    use DatabaseMigrations;

    public function testEnumValuesShouldMatchRoleIdsOnDd()
    {
        $readerId = Role::firstWhere('name', 'reader')->id;
        $writerId = Role::firstWhere('name', 'writer')->id;
        $moderatorId = Role::firstWhere('name', 'moderator')->id;
        $adminId = Role::firstWhere('name', 'admin')->id;

        $this->assertEquals(Roles::READER, $readerId);
        $this->assertEquals(Roles::WRITER, $writerId);
        $this->assertEquals(Roles::MODERATOR, $moderatorId);
        $this->assertEquals(Roles::ADMIN, $adminId);
    }
}
