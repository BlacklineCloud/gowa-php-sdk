<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

/** @psalm-immutable */
final readonly class NewsletterResponse
{
    /** @param list<Newsletter> $newsletters */
    public function __construct(
        public string $code,
        public string $message,
        public array $newsletters,
    ) {
    }
}
