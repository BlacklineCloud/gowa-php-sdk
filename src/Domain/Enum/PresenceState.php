<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Enum;

enum PresenceState: string
{
    case Available = 'available';
    case Unavailable = 'unavailable';
    case Composing = 'composing';
    case Recording = 'recording';
    case Paused = 'paused';
}
