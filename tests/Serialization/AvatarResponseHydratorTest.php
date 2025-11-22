<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\AvatarResponseHydrator;
use PHPUnit\Framework\TestCase;

final class AvatarResponseHydratorTest extends TestCase
{
    public function testHydratesAvatar(): void
    {
        $hydrator = new AvatarResponseHydrator();
        $dto      = $hydrator->hydrate([
            'code'    => 'SUCCESS',
            'message' => 'Success',
            'results' => [
                'avatar' => 'http://example.test/avatar.png',
            ],
        ]);

        self::assertSame('http://example.test/avatar.png', $dto->avatarUrl);
    }
}
