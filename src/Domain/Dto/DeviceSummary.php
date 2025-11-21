<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

final readonly class DeviceSummary
{
    public function __construct(
        public string $name,
        public string $device,
    ) {
    }
}
