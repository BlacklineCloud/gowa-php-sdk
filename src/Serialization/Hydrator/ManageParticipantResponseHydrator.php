<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\ManageParticipantResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\ManageParticipantResult;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class ManageParticipantResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): ManageParticipantResponse
    {
        $reader     = new ArrayReader($payload);
        $resultsRaw = $reader->requireObject('results');
        $results    = [];
        foreach ($resultsRaw as $row) {
            $rowReader = new ArrayReader((array) $row, '$.results');
            $results[] = new ManageParticipantResult(
                participant: $rowReader->requireString('participant'),
                status: $rowReader->requireString('status'),
                message: $rowReader->requireString('message'),
            );
        }

        return new ManageParticipantResponse(
            code: $reader->requireString('code'),
            message: $reader->requireString('message'),
            results: $results,
        );
    }
}
