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

    public function testGetRoleFromStringMethodShouldReturnRoleId()
    {
        $this->assertEquals(1, Roles::getRoleFromString('reader'));
        $this->assertEquals(2, Roles::getRoleFromString('writer'));
        $this->assertEquals(3, Roles::getRoleFromString('moderator'));
        $this->assertEquals(4, Roles::getRoleFromString('admin'));
    }
}
