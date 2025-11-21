<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

/** @psalm-immutable */
final readonly class ChatMessages
{
    /** @param list<ChatMessage> $data */
    public function __construct(
        public array $data,
        public Pagination $pagination,
        public Chat $chatInfo,
    ) {
    }
}
