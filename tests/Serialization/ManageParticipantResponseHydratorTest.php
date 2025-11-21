<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\ManageParticipantResponseHydrator;
use PHPUnit\Framework\TestCase;

final class ManageParticipantResponseHydratorTest extends TestCase
{
    public function testHydratesManageParticipants(): void
    {
        $hydrator = new ManageParticipantResponseHydrator();
        $dto = $hydrator->hydrate([
            'code' => 'SUCCESS',
            'message' => 'Success get list groups',
            'results' => [
                [
                    'participant' => '6289987391723@s.whatsapp.net',
                    'status' => 'success',
                    'message' => 'Participant added',
                ],
            ],
        ]);

        self::assertSame('success', $dto->results[0]->status);
    }
}
