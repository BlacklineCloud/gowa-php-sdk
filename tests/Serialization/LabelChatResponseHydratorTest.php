<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\LabelChatResponseHydrator;
use PHPUnit\Framework\TestCase;

final class LabelChatResponseHydratorTest extends TestCase
{
    public function testHydratesLabelChat(): void
    {
        $hydrator = new LabelChatResponseHydrator();
        $dto      = $hydrator->hydrate([
            'code'    => 'SUCCESS',
            'message' => "Chat labeled successfully with label 'Important'",
            'results' => [
                'status'   => 'success',
                'message'  => "Chat labeled successfully with label 'Important'",
                'chat_jid' => '6289685028129@s.whatsapp.net',
                'label_id' => 'label_123',
                'labeled'  => true,
            ],
        ]);

        self::assertTrue($dto->results->labeled);
        self::assertSame('label_123', $dto->results->labelId);
    }
}
