<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\AvatarResponse;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class AvatarResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): AvatarResponse
    {
        $reader = new ArrayReader($payload);

        return new AvatarResponse(
            $reader->requireString('code'),
            $reader->requireString('message'),
            (new ArrayReader($reader->requireObject('results'), '$.results'))->requireString('avatar'),
        );
    }
}
