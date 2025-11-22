<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\BusinessProfileResponseHydrator;
use PHPUnit\Framework\TestCase;

final class BusinessProfileResponseHydratorTest extends TestCase
{
    public function testHydratesBusinessProfile(): void
    {
        $hydrator = new BusinessProfileResponseHydrator();
        $dto      = $hydrator->hydrate([
            'code'    => 'SUCCESS',
            'message' => 'Success get business profile',
            'results' => [
                'description'    => 'We sell things',
                'email'          => 'contact@example.test',
                'website'        => 'https://example.test',
                'business_hours' => '09:00-18:00',
            ],
        ]);

        self::assertSame('We sell things', $dto->profile->description);
        self::assertSame('SUCCESS', $dto->code);
    }
}
