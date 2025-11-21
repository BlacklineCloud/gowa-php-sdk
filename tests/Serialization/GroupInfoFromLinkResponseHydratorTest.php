<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupInfoFromLinkResponseHydrator;
use PHPUnit\Framework\TestCase;

final class GroupInfoFromLinkResponseHydratorTest extends TestCase
{
    public function testHydratesGroupInfoFromLink(): void
    {
        $hydrator = new GroupInfoFromLinkResponseHydrator();
        $dto = $hydrator->hydrate([
            'code' => 'SUCCESS',
            'message' => 'Success get group info from link',
            'results' => [
                'group_id' => '120363024512399999@g.us',
                'name' => 'Example Group Name',
                'topic' => 'Welcome to our group! Please follow the rules.',
                'created_at' => '2024-01-15T10:30:00Z',
                'participant_count' => 25,
                'is_locked' => false,
                'is_announce' => false,
                'is_ephemeral' => false,
                'description' => 'This group is for discussing project updates',
            ],
        ]);

        self::assertSame('Example Group Name', $dto->results->name);
        self::assertSame(25, $dto->results->participantCount);
    }
}
