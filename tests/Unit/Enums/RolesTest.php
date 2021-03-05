<?php

namespace Tests\Unit\Enums;

use App\Models\Role;
use App\Enums\Roles;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RolesTest extends TestCase
{
    use DatabaseMigrations;

    public function testGetEnumFromStringMethodShouldReturnRoleId()
    {
        $this->assertEquals("READER", Roles::getEnumFromString('reader'));
        $this->assertEquals("WRITER", Roles::getEnumFromString('writer'));
        $this->assertEquals("MODERATOR", Roles::getEnumFromString('moderator'));
        $this->assertEquals("ADMIN", Roles::getEnumFromString('admin'));
    }
}
