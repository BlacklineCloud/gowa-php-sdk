<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

/** @psalm-immutable */
final readonly class DevicesResponse
{
    /** @param list<DeviceSummary> $devices */
    public function __construct(
        public string $code,
        public string $message,
        public array $devices,
    ) {
    }
}
