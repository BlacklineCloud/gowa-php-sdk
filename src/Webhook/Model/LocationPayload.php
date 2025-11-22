<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Webhook\Model;

final readonly class LocationPayload
{
    public function __construct(
        public float $latitude,
        public float $longitude,
        public ?string $name,
        public ?string $address,
    ) {
    }
}
