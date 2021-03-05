<?php

namespace Tests\Unit\Enums;

use Tests\TestCase;
use App\Enums\ArticleStatus;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ArticleStatusTest extends TestCase
{
    use DatabaseMigrations;

    public function testGetEnumFromStringMethodShouldReturnStatusString()
    {
        $this->assertEquals("DRAFT", ArticleStatus::getEnumFromString('DRAFT'));
        $this->assertEquals("PUBLISHED", ArticleStatus::getEnumFromString('PUBLISHED'));
        $this->assertEquals("SCHEDULED", ArticleStatus::getEnumFromString('SCHEDULED'));
    }
}
