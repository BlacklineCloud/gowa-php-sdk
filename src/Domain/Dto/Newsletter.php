<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

final readonly class Newsletter
{
    public function __construct(
        public string $id,
        public string $stateType,
        public ?string $name,
        public ?string $description,
        public ?int $subscribersCount,
        public ?string $verification,
        public ?string $pictureUrl,
        public ?string $previewUrl,
    ) {
    }
}
