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
        $r = new ArrayReader($payload);
        return new GenericResponse(
            $r->requireString('code'),
            $r->requireString('message'),
            $r->optionalString('results'),
        );
    }
}
