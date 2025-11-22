<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\MessageActionResponseHydrator;
use PHPUnit\Framework\TestCase;

final class MessageActionResponseHydratorTest extends TestCase
{
    public function testHydratesMessageAction(): void
    {
        $hydrator = new MessageActionResponseHydrator();
        $dto      = $hydrator->hydrate([
            'code'    => 'SUCCESS',
            'message' => 'Message revoked successfully',
            'results' => [
                'status'     => 'success',
                'message'    => 'Message revoked',
                'message_id' => '3EB0B430B6F8F1D0E053AC120E0A9E5C'
            ],
        ]);

        self::assertSame('success', $dto->results->status);
        self::assertSame('3EB0B430B6F8F1D0E053AC120E0A9E5C', $dto->results->messageId);
    }
}
