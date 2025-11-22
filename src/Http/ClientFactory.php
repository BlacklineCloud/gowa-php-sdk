<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Http;

use BlacklineCloud\SDK\GowaPHP\Client\AppClient;
use BlacklineCloud\SDK\GowaPHP\Client\ChatClient;
use BlacklineCloud\SDK\GowaPHP\Client\GroupClient;
use BlacklineCloud\SDK\GowaPHP\Client\MessageClient;
use BlacklineCloud\SDK\GowaPHP\Client\NewsletterClient;
use BlacklineCloud\SDK\GowaPHP\Client\SendClient;
use BlacklineCloud\SDK\GowaPHP\Client\UserClient;
use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Http\Middleware\AuthMiddleware;
use BlacklineCloud\SDK\GowaPHP\Http\Middleware\CorrelationIdMiddleware;
use BlacklineCloud\SDK\GowaPHP\Http\Middleware\IdempotencyMiddleware;
use BlacklineCloud\SDK\GowaPHP\Http\Middleware\LoggingMiddleware;
use BlacklineCloud\SDK\GowaPHP\Http\Middleware\RetryMiddleware;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\AvatarResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\BusinessProfileResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\ChatListResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\ChatMessagesResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\CreateGroupResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\DevicesResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GenericResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupInfoFromLinkResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupInfoResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupInviteLinkResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupListResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupParticipantRequestsResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupParticipantsResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\LabelChatResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\LoginResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\LoginWithCodeResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\ManageParticipantResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\MessageActionResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\MyContactsResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\NewsletterResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\PinChatResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\PrivacyResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\SendResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\SetGroupPhotoResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\UserCheckResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\UserInfoResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Support\UuidGeneratorInterface;
use Psr\Http\Client\ClientInterface as Psr18Client;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Log\LoggerInterface;

final class ClientFactory
{
    public function __construct(
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory,
        private readonly Psr18Client $psr18,
        private readonly LoggerInterface $logger,
        private readonly UuidGeneratorInterface $uuid,
    ) {
    }

    public function createTransport(ClientConfig $config): Psr18Transport
    {
        return new Psr18Transport(
            $this->psr18,
            $config,
            $this->logger,
            new AuthMiddleware($config),
            new CorrelationIdMiddleware($this->uuid),
            new IdempotencyMiddleware($this->uuid),
            new LoggingMiddleware($this->logger),
            new RetryMiddleware($config),
        );
    }

    public function createAppClient(ClientConfig $config): AppClient
    {
        $transport = $this->createTransport($config);

        return new AppClient(
            $config,
            $transport,
            $this->requestFactory,
            $this->streamFactory,
            new LoginResponseHydrator(),
            new LoginWithCodeResponseHydrator(),
            new DevicesResponseHydrator(),
            new GenericResponseHydrator(),
        );
    }

    public function createSendClient(ClientConfig $config): SendClient
    {
        $transport = $this->createTransport($config);

        return new SendClient(
            $config,
            $transport,
            $this->requestFactory,
            $this->streamFactory,
            new SendResponseHydrator(),
        );
    }

    public function createUserClient(ClientConfig $config): UserClient
    {
        $transport = $this->createTransport($config);

        return new UserClient(
            $config,
            $transport,
            $this->requestFactory,
            $this->streamFactory,
            new UserInfoResponseHydrator(),
            new AvatarResponseHydrator(),
            new PrivacyResponseHydrator(),
            new MyContactsResponseHydrator(),
            new BusinessProfileResponseHydrator(),
            new UserCheckResponseHydrator(),
            new GenericResponseHydrator(),
        );
    }

    public function createMessageClient(ClientConfig $config): MessageClient
    {
        $transport = $this->createTransport($config);

        return new MessageClient(
            $config,
            $transport,
            $this->requestFactory,
            $this->streamFactory,
            new MessageActionResponseHydrator(),
            new GenericResponseHydrator(),
        );
    }

    public function createChatClient(ClientConfig $config): ChatClient
    {
        $transport = $this->createTransport($config);

        return new ChatClient(
            $config,
            $transport,
            $this->requestFactory,
            $this->streamFactory,
            new ChatListResponseHydrator(),
            new ChatMessagesResponseHydrator(),
            new LabelChatResponseHydrator(),
            new PinChatResponseHydrator(),
        );
    }

    public function createGroupClient(ClientConfig $config): GroupClient
    {
        $transport = $this->createTransport($config);

        return new GroupClient(
            $config,
            $transport,
            $this->requestFactory,
            $this->streamFactory,
            new CreateGroupResponseHydrator(),
            new GroupListResponseHydrator(),
            new GroupParticipantsResponseHydrator(),
            new ManageParticipantResponseHydrator(),
            new GroupInfoFromLinkResponseHydrator(),
            new GroupInfoResponseHydrator(),
            new GroupInviteLinkResponseHydrator(),
            new SetGroupPhotoResponseHydrator(),
            new GroupParticipantRequestsResponseHydrator(),
            new GenericResponseHydrator(),
        );
    }

    public function createNewsletterClient(ClientConfig $config): NewsletterClient
    {
        $transport = $this->createTransport($config);

        return new NewsletterClient(
            $config,
            $transport,
            $this->requestFactory,
            $this->streamFactory,
            new NewsletterResponseHydrator(),
            new GenericResponseHydrator(),
        );
    }
}
