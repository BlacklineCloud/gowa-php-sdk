<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

interface HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): object;
}
