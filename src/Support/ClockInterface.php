<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Support;

interface ClockInterface
{
    public function now(): \DateTimeImmutable;
}
