<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\UserInfoResponseHydrator;
use PHPUnit\Framework\TestCase;

final class UserInfoResponseHydratorTest extends TestCase
{
    public function testHydratesUserInfo(): void
    {
        $hydrator = new UserInfoResponseHydrator();
        $dto      = $hydrator->hydrate([
            'code'    => 'SUCCESS',
            'message' => 'Success get info',
            'results' => [
                'pushname'      => 'Alice',
                'verified'      => 1,
                'lid'           => '123@lid',
                'business_name' => 'Alice Store',
            ],
        ]);

        self::assertSame('Alice', $dto->user->pushName);
        self::assertTrue($dto->user->verified);
        self::assertSame('SUCCESS', $dto->code);
    }
}
