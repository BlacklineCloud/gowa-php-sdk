<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\SetGroupPhotoResponseHydrator;
use PHPUnit\Framework\TestCase;

final class SetGroupPhotoResponseHydratorTest extends TestCase
{
    public function testHydratesGroupPhoto(): void
    {
        $hydrator = new SetGroupPhotoResponseHydrator();
        $dto      = $hydrator->hydrate([
            'code'    => 'SUCCESS',
            'message' => 'Success update group photo',
            'results' => [
                'picture_id' => '1647874123',
                'message'    => 'Success update group photo',
            ],
        ]);

        self::assertSame('1647874123', $dto->pictureId);
    }
}
