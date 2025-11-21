<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Enum;

enum GroupActionType: string
{
    case Join = 'join';
    case Leave = 'leave';
    case Promote = 'promote';
    case Demote = 'demote';
}
