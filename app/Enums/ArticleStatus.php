<?php

namespace App\Enums;

use App\Enums\BaseEnum;

abstract class ArticleStatus extends BaseEnum
{
    public const DRAFT = 'DRAFT';
    public const PUBLISHED = 'PUBLISHED';
    public const SCHEDULED = 'SCHEDULED';
}
