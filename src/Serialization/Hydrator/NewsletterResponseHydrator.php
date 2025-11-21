<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\Newsletter;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\NewsletterResponse;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class NewsletterResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): NewsletterResponse
    {
        $r = new ArrayReader($payload);
        $results = new ArrayReader($r->requireObject('results'), '$.results');
        $data = $results->requireObject('data');

        $items = [];
        foreach ($data as $row) {
            $rowR = new ArrayReader((array) $row, '$.results.data');
            $state = new ArrayReader((array) $rowR->requireObject('state'), '$.results.data.state');
            $thread = new ArrayReader((array) $rowR->requireObject('thread_metadata'), '$.results.data.thread_metadata');

            $items[] = new Newsletter(
                id: $rowR->requireString('id'),
                stateType: $state->requireString('type'),
                name: $this->nestedText($thread, 'name'),
                description: $this->nestedText($thread, 'description'),
                subscribersCount: $this->nestedInt($thread->optionalString('subscribers_count')),
                verification: $thread->optionalString('verification'),
                pictureUrl: $this->nestedUrl($thread->optionalObject('picture')),
                previewUrl: $this->nestedUrl($thread->optionalObject('preview')),
            );
        }

        return new NewsletterResponse(
            code: $r->requireString('code'),
            message: $r->requireString('message'),
            newsletters: $items,
        );
    }

    private function nestedText(ArrayReader $reader, string $key): ?string
    {
        $obj = $reader->optionalObject($key);
        if ($obj !== null) {
            $r = new ArrayReader($obj, '$.results.data.thread_metadata.' . $key);
            return $r->optionalString('text');
        }

        return $reader->optionalString($key);
    }

    private function nestedInt(?string $value): ?int
    {
        return $value === null ? null : (int) $value;
    }

    /** @param array<string,mixed>|null $obj */
    private function nestedUrl(?array $obj): ?string
    {
        if ($obj === null) {
            return null;
        }

        $r = new ArrayReader($obj);
        return $r->optionalString('url');
    }
}
