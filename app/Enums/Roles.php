<?php

namespace App\Enums;

use App\Enums\BaseEnum;

abstract class Roles extends BaseEnum
{
    public const READER = "READER";
    public const WRITER = "WRITER";
    public const MODERATOR = "MODERATOR";
    public const ADMIN = "ADMIN";
}
