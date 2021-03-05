<?php

namespace App\Enums;

use App\Enums\BaseEnum;

abstract class Roles extends BaseEnum
{
    public const READER = 1;
    public const WRITER = 2;
    public const MODERATOR = 3;
    public const ADMIN = 4;
}
