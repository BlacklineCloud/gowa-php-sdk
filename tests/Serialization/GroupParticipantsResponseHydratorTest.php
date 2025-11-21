<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupParticipantsResponseHydrator;
use PHPUnit\Framework\TestCase;

final class GroupParticipantsResponseHydratorTest extends TestCase
{
    public function testHydratesGroupParticipants(): void
    {
        $hydrator = new GroupParticipantsResponseHydrator();
        $dto = $hydrator->hydrate([
            'code' => 'SUCCESS',
            'message' => 'Success getting group participants',
            'results' => [
                'group_id' => '120363024512399999@g.us',
                'name' => 'My Awesome Group',
                'participants' => [
                    [
                        'jid' => '6289987391723@s.whatsapp.net',
                        'phone_number' => '6289987391723@s.whatsapp.net',
                        'lid' => null,
                        'display_name' => 'Anon',
                        'is_admin' => true,
                        'is_super_admin' => false,
                    ],
                ],
            ],
        ]);

        self::assertSame('120363024512399999@g.us', $dto->results->groupId);
        self::assertTrue($dto->results->participants[0]->isAdmin);
    }
}
