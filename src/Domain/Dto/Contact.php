<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

final readonly class Contact
{
    public function __construct(
        public string $jid,
        public string $name,
    ) {
    }
}
