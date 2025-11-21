<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupParticipantRequestsResponseHydrator;
use PHPUnit\Framework\TestCase;

final class GroupParticipantRequestsResponseHydratorTest extends TestCase
{
    public function testHydratesRequests(): void
    {
        $hydrator = new GroupParticipantRequestsResponseHydrator();
        $dto = $hydrator->hydrate([
            'code' => 'SUCCESS',
            'message' => 'Success getting list requested participants',
            'results' => [
                'data' => [
                    [
                        'jid' => '6289685024091@s.whatsapp.net',
                        'requested_at' => '2024-10-11T21:27:29+07:00',
                    ],
                ],
            ],
        ]);

        self::assertSame('6289685024091@s.whatsapp.net', $dto->requests[0]->jid);
    }
}
