<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\BusinessProfile;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\BusinessProfileResponse;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class BusinessProfileResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): BusinessProfileResponse
    {
        $reader  = new ArrayReader($payload);
        $results = new ArrayReader($reader->requireObject('results'), '$.results');

        return new BusinessProfileResponse(
            $reader->requireString('code'),
            $reader->requireString('message'),
            new BusinessProfile(
                description: $results->optionalString('description'),
                email: $results->optionalString('email'),
                website: $results->optionalString('website'),
                businessHours: $results->optionalString('business_hours'),
            ),
        );
    }
}
