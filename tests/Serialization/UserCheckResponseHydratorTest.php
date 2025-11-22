<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\UserCheckResponseHydrator;
use PHPUnit\Framework\TestCase;

final class UserCheckResponseHydratorTest extends TestCase
{
    public function testHydratesUserCheck(): void
    {
        $hydrator = new UserCheckResponseHydrator();
        $dto      = $hydrator->hydrate([
            'code'    => 'SUCCESS',
            'message' => 'Success check user',
            'results' => [
                'is_on_whatsapp' => 1,
            ],
        ]);

        self::assertTrue($dto->isOnWhatsapp);
    }
}
