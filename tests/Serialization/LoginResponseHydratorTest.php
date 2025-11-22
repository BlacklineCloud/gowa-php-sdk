<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\LoginResponseHydrator;
use PHPUnit\Framework\TestCase;

final class LoginResponseHydratorTest extends TestCase
{
    public function testHydrates(): void
    {
        $hydrator = new LoginResponseHydrator();
        $dto      = $hydrator->hydrate([
            'code'    => 'SUCCESS',
            'message' => 'Success',
            'results' => [
                'qr_duration' => 30,
                'qr_link'     => 'http://example.test/qr.png',
            ],
        ]);

        self::assertSame('SUCCESS', $dto->code);
        self::assertSame(30, $dto->qrDuration);
        self::assertSame('http://example.test/qr.png', $dto->qrLink);
    }
}
