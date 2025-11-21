<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\CreateGroupResponseHydrator;
use PHPUnit\Framework\TestCase;

final class CreateGroupResponseHydratorTest extends TestCase
{
    public function testHydratesCreateGroup(): void
    {
        $hydrator = new CreateGroupResponseHydrator();
        $dto = $hydrator->hydrate([
            'code' => 'SUCCESS',
            'message' => 'Success get list groups',
            'results' => [
                'group_id' => '1203632782168851111@g.us',
            ],
        ]);

        self::assertSame('1203632782168851111@g.us', $dto->groupId);
    }
}
