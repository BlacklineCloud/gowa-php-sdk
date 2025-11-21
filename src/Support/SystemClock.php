<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Support;

final class SystemClock implements ClockInterface
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now');
    }
}
