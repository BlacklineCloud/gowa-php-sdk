<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

final readonly class ManageParticipantResult
{
    public function __construct(
        public string $participant,
        public string $status,
        public string $message,
    ) {
    }
}
