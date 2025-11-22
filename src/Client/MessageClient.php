<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Client;

use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Contracts\Http\HttpTransportInterface;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GenericResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\MessageActionResponse;
use BlacklineCloud\SDK\GowaPHP\Http\ApiClient;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GenericResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\MessageActionResponseHydrator;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class MessageClient extends ApiClient
{
    public function __construct(
        ClientConfig $config,
        HttpTransportInterface $transport,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        private readonly MessageActionResponseHydrator $actionHydrator,
        private readonly GenericResponseHydrator $genericHydrator,
    ) {
        parent::__construct($config, $transport, $requestFactory, $streamFactory);
    }

    public function revoke(string $messageId): MessageActionResponse
    {
        return $this->actionHydrator->hydrate($this->post("/message/{$messageId}/revoke"));
    }

    public function delete(string $messageId): MessageActionResponse
    {
        return $this->actionHydrator->hydrate($this->post("/message/{$messageId}/delete"));
    }

    public function reaction(string $messageId, string $emoji): MessageActionResponse
    {
        return $this->actionHydrator->hydrate($this->post("/message/{$messageId}/reaction", ['reaction' => $emoji]));
    }

    public function update(string $messageId, string $text): MessageActionResponse
    {
        return $this->actionHydrator->hydrate($this->post("/message/{$messageId}/update", ['text' => $text]));
    }

    public function read(string $messageId): MessageActionResponse
    {
        return $this->actionHydrator->hydrate($this->post("/message/{$messageId}/read"));
    }

    public function star(string $messageId): GenericResponse
    {
        return $this->genericHydrator->hydrate($this->post("/message/{$messageId}/star"));
    }

    public function unstar(string $messageId): GenericResponse
    {
        return $this->genericHydrator->hydrate($this->post("/message/{$messageId}/unstar"));
    }
}
