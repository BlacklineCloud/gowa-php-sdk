<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupListResponseHydrator;
use PHPUnit\Framework\TestCase;

final class GroupListResponseHydratorTest extends TestCase
{
    public function testHydratesGroupList(): void
    {
        $hydrator = new GroupListResponseHydrator();
        $dto      = $hydrator->hydrate([
            'code'    => 'SUCCESS',
            'message' => 'Success get list groups',
            'results' => [
                'data' => [
                    [
                        'JID'                    => '120363347168689807@g.us',
                        'OwnerJID'               => '6288228744537@s.whatsapp.net',
                        'Name'                   => 'Example Group',
                        'NameSetAt'              => '2024-10-11T21:27:29+07:00',
                        'IsLocked'               => false,
                        'IsAnnounce'             => false,
                        'IsEphemeral'            => false,
                        'DisappearingTimer'      => 0,
                        'IsIncognito'            => false,
                        'IsParent'               => false,
                        'IsDefaultSubGroup'      => false,
                        'IsJoinApprovalRequired' => false,
                        'GroupCreated'           => '2024-10-11T21:27:29+07:00',
                        'Participants'           => [
                            [
                                'JID'          => '6288228744537@s.whatsapp.net',
                                'LID'          => '20036609675500@lid',
                                'IsAdmin'      => true,
                                'IsSuperAdmin' => true,
                                'DisplayName'  => '',
                                'Error'        => 0,
                                'AddRequest'   => null
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        self::assertSame('Example Group', $dto->groups[0]->name);
        self::assertTrue($dto->groups[0]->participants[0]->isAdmin);
    }
}
