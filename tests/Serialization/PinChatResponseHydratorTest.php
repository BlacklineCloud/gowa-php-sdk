<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\PinChatResponseHydrator;
use PHPUnit\Framework\TestCase;

final class PinChatResponseHydratorTest extends TestCase
{
    public function testHydratesPinChat(): void
    {
        $hydrator = new PinChatResponseHydrator();
        $dto = $hydrator->hydrate([
            'code' => 'SUCCESS',
            'message' => 'Chat pinned successfully',
            'results' => [
                'status' => 'success',
                'message' => 'Chat pinned successfully',
                'chat_jid' => '6289685028129@s.whatsapp.net',
                'pinned' => true,
            ],
        ]);

        self::assertTrue($dto->results->pinned);
        self::assertSame('6289685028129@s.whatsapp.net', $dto->results->chatJid);
    }
}
