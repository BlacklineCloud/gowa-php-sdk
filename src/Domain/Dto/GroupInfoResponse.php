<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

final readonly class GroupInfoResponse
{
    /**
     * @param array<string,mixed> $results
     */
    public function __construct(
        public int $status,
        public string $code,
        public string $message,
        public array $results,
    ) {
    }
}
