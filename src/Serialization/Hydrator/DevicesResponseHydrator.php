<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\DeviceSummary;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\DevicesResponse;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class DevicesResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): DevicesResponse
    {
        $reader = new ArrayReader($payload);
        $code = $reader->requireString('code');
        $message = $reader->requireString('message');
        $results = new ArrayReader($reader->requireObject('results'), '$.results');
        $devicesRaw = $results->requireObject('devices');

        $devices = [];
        foreach ($devicesRaw as $row) {
            $rowReader = new ArrayReader((array) $row, '$.results.devices');
            $devices[] = new DeviceSummary(
                name: $rowReader->requireString('name'),
                device: $rowReader->requireString('device'),
            );
        }

        return new DevicesResponse($code, $message, $devices);
    }
}
