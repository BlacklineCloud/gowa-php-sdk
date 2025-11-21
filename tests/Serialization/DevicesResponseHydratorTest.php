<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\DevicesResponseHydrator;
use PHPUnit\Framework\TestCase;

final class DevicesResponseHydratorTest extends TestCase
{
    public function testHydratesDevices(): void
    {
        $hydrator = new DevicesResponseHydrator();
        $dto = $hydrator->hydrate([
            'code' => 'SUCCESS',
            'message' => 'Success',
            'results' => [
                'devices' => [
                    ['name' => 'Aldino Kemal', 'device' => '628960561XXX.0:64@s.whatsapp.net'],
                    ['name' => 'Another', 'device' => '123@s.whatsapp.net'],
                ],
            ],
        ]);

        self::assertCount(2, $dto->devices);
        self::assertSame('Aldino Kemal', $dto->devices[0]->name);
        self::assertSame('SUCCESS', $dto->code);
    }
}
