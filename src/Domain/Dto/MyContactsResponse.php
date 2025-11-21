<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

/** @psalm-immutable */
final readonly class MyContactsResponse
{
    /** @param list<Contact> $contacts */
    public function __construct(
        public string $code,
        public string $message,
        public array $contacts,
    ) {
    }
}
