<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

/** @psalm-immutable */
final readonly class ManageParticipantResponse
{
    /** @param list<ManageParticipantResult> $results */
    public function __construct(
        public string $code,
        public string $message,
        public array $results,
    ) {
    }
}
