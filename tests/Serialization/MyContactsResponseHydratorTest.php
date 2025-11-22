<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\MyContactsResponseHydrator;
use PHPUnit\Framework\TestCase;

final class MyContactsResponseHydratorTest extends TestCase
{
    public function testHydratesContacts(): void
    {
        $hydrator = new MyContactsResponseHydrator();
        $dto      = $hydrator->hydrate([
            'code'    => 'SUCCESS',
            'message' => 'Success get list contacts',
            'results' => [
                'data' => [
                    ['jid' => '628123123123123@s.whatsapp.net', 'name' => 'Aldino Kemal'],
                ],
            ],
        ]);

        self::assertSame('SUCCESS', $dto->code);
        self::assertSame('Aldino Kemal', $dto->contacts[0]->name);
    }
}
