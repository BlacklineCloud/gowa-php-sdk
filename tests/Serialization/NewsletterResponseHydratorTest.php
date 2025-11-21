<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\NewsletterResponseHydrator;
use PHPUnit\Framework\TestCase;

final class NewsletterResponseHydratorTest extends TestCase
{
    public function testHydratesNewsletterList(): void
    {
        $hydrator = new NewsletterResponseHydrator();
        $dto = $hydrator->hydrate([
            'code' => 'SUCCESS',
            'message' => 'Success get list newsletter',
            'results' => [
                'data' => [
                    [
                        'id' => '120363144038483540@newsletter',
                        'state' => [
                            'type' => 'active',
                        ],
                        'thread_metadata' => [
                            'name' => ['text' => 'WhatsApp'],
                            'description' => ['text' => 'Official channel'],
                            'subscribers_count' => '10',
                            'verification' => 'verified',
                            'picture' => ['url' => 'http://example.test/pic.jpg'],
                            'preview' => ['url' => 'http://example.test/prev.jpg'],
                        ],
                    ],
                ],
            ],
        ]);

        self::assertSame('SUCCESS', $dto->code);
        self::assertSame('WhatsApp', $dto->newsletters[0]->name);
        self::assertSame(10, $dto->newsletters[0]->subscribersCount);
    }
}
