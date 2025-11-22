<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GenericResponseHydrator;
use PHPUnit\Framework\TestCase;

final class GenericResponseHydratorTest extends TestCase
{
    public function testHydratesGeneric(): void
    {
        $hydrator = new GenericResponseHydrator();
        $dto      = $hydrator->hydrate([
            'code'    => 'SUCCESS',
            'message' => 'OK',
            'results' => null,
        ]);

        self::assertSame('SUCCESS', $dto->code);
        self::assertNull($dto->results);
    }
}
