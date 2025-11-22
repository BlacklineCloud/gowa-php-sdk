<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\Contact;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\MyContactsResponse;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class MyContactsResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): MyContactsResponse
    {
        $r        = new ArrayReader($payload);
        $results  = new ArrayReader($r->requireObject('results'), '$.results');
        $data     = $results->requireObject('data');
        $contacts = [];
        foreach ($data as $row) {
            $rowR       = new ArrayReader((array) $row, '$.results.data');
            $contacts[] = new Contact(
                jid: $rowR->requireString('jid'),
                name: $rowR->requireString('name'),
            );
        }

        return new MyContactsResponse(
            $r->requireString('code'),
            $r->requireString('message'),
            $contacts,
        );
    }
}
