<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GenericResponse;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class GenericResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): GenericResponse
    {
        $r       = new ArrayReader($payload);
        $results = $payload['results'] ?? null;
        if ($results !== null && !\is_string($results) && !\is_array($results)) {
            throw new \InvalidArgumentException('Expected results to be string|array|null');
        }
        return new GenericResponse(
            $r->requireString('code'),
            $r->requireString('message'),
            $results,
        );
    }
}
