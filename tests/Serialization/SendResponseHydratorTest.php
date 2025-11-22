<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\SendResponseHydrator;
use PHPUnit\Framework\TestCase;

final class SendResponseHydratorTest extends TestCase
{
    public function testHydrates(): void
    {
        $hydrator = new SendResponseHydrator();
        $dto      = $hydrator->hydrate([
            'code'    => 'SUCCESS',
            'message' => 'Success',
            'results' => [
                'message_id' => 'ABC',
                'status'     => 'sent',
            ],
        ]);

        self::assertSame('SUCCESS', $dto->code);
        self::assertSame('ABC', $dto->messageId);
        self::assertSame('sent', $dto->status);
    }
}
