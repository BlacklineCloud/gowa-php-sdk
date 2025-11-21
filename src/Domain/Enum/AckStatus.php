<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Enum;

enum AckStatus: string
{
    case Delivered = 'delivered';
    case Read = 'read';
    case Played = 'played';
}
