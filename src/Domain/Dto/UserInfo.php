<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

final readonly class UserInfo
{
    public function __construct(
        public string $pushName,
        public bool $verified,
        public ?string $lid,
        public ?string $businessName,
    ) {
    }
}
