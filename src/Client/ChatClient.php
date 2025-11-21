<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Client;

use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Contracts\Http\HttpTransportInterface;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\ChatListResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\ChatMessagesResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\LabelChatResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\PinChatResponse;
use BlacklineCloud\SDK\GowaPHP\Http\ApiClient;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\ChatListResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\ChatMessagesResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\LabelChatResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\PinChatResponseHydrator;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class ChatClient extends ApiClient
{
    public function __construct(
        ClientConfig $config,
        HttpTransportInterface $transport,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        private readonly ChatListResponseHydrator $listHydrator,
        private readonly ChatMessagesResponseHydrator $messagesHydrator,
        private readonly LabelChatResponseHydrator $labelHydrator,
        private readonly PinChatResponseHydrator $pinHydrator,
    ) {
        parent::__construct($config, $transport, $requestFactory, $streamFactory);
    }

    public function list(int $limit = 25, int $offset = 0, ?string $search = null, bool $hasMedia = false): ChatListResponse
    {
        $query = ['limit' => $limit, 'offset' => $offset, 'has_media' => $hasMedia ? 'true' : 'false'];
        if ($search !== null) {
            $query['search'] = $search;
        }

        return $this->listHydrator->hydrate($this->get('/chats', $query));
    }

    public function messages(string $chatJid, int $limit = 50, int $offset = 0): ChatMessagesResponse
    {
        return $this->messagesHydrator->hydrate($this->get("/chat/{$chatJid}/messages", [
            'limit' => $limit,
            'offset' => $offset,
        ]));
    }

    public function label(string $chatJid, string $labelId): LabelChatResponse
    {
        return $this->labelHydrator->hydrate($this->post("/chat/{$chatJid}/label", ['label_id' => $labelId]));
    }

    public function pin(string $chatJid, bool $pinned = true): PinChatResponse
    {
        return $this->pinHydrator->hydrate($this->post("/chat/{$chatJid}/pin", ['pinned' => $pinned]));
    }
}
