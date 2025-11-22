<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupInviteLinkResponseHydrator;
use PHPUnit\Framework\TestCase;

final class GroupInviteLinkResponseHydratorTest extends TestCase
{
    public function testHydratesInviteLink(): void
    {
        $hydrator = new GroupInviteLinkResponseHydrator();
        $dto      = $hydrator->hydrate([
            'code'    => 'SUCCESS',
            'message' => 'Success get group invite link',
            'results' => [
                'invite_link' => 'https://chat.whatsapp.com/ABC123',
                'group_id'    => '120363025982934543@g.us',
            ],
        ]);

        self::assertSame('https://chat.whatsapp.com/ABC123', $dto->inviteLink);
    }
}
