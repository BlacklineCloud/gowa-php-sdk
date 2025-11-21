<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Enum;

enum ReceiptType: string
{
    case Delivered = 'delivered';
    case Read = 'read';
}
