<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupInfoResponseHydrator;
use PHPUnit\Framework\TestCase;

final class GroupInfoResponseHydratorTest extends TestCase
{
    public function testHydratesGroupInfo(): void
    {
        $hydrator = new GroupInfoResponseHydrator();
        $dto      = $hydrator->hydrate([
            'status'  => 200,
            'code'    => 'SUCCESS',
            'message' => 'ok',
            'results' => ['subject' => 'My Group'],
        ]);

        self::assertSame(200, $dto->status);
        self::assertSame('SUCCESS', $dto->code);
        self::assertSame('My Group', $dto->results['subject']);
    }
}
