<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\PrivacyResponseHydrator;
use PHPUnit\Framework\TestCase;

final class PrivacyResponseHydratorTest extends TestCase
{
    public function testHydratesPrivacy(): void
    {
        $hydrator = new PrivacyResponseHydrator();
        $dto      = $hydrator->hydrate([
            'code'    => 'SUCCESS',
            'message' => 'Success get privacy',
            'results' => [
                'group_add'     => 'all',
                'last_seen'     => null,
                'status'        => 'all',
                'profile'       => 'contacts',
                'read_receipts' => 'all',
            ],
        ]);

        self::assertSame('SUCCESS', $dto->code);
        self::assertSame('contacts', $dto->settings->profile);
    }
}
