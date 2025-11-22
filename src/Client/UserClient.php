<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Client;

use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Contracts\Http\HttpTransportInterface;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\AvatarResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\BusinessProfileResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\MyContactsResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\PrivacyResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\UserCheckResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\UserInfoResponse;
use BlacklineCloud\SDK\GowaPHP\Http\ApiClient;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\AvatarResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\BusinessProfileResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\MyContactsResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\PrivacyResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\UserCheckResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\UserInfoResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Support\InputValidator;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class UserClient extends ApiClient
{
    public function __construct(
        ClientConfig $config,
        HttpTransportInterface $transport,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        private readonly UserInfoResponseHydrator $infoHydrator,
        private readonly AvatarResponseHydrator $avatarHydrator,
        private readonly PrivacyResponseHydrator $privacyHydrator,
        private readonly MyContactsResponseHydrator $contactsHydrator,
        private readonly BusinessProfileResponseHydrator $businessProfileHydrator,
        private readonly UserCheckResponseHydrator $userCheckHydrator,
    ) {
        parent::__construct($config, $transport, $requestFactory, $streamFactory);
    }

    public function info(): UserInfoResponse
    {
        return $this->infoHydrator->hydrate($this->get('/user/info'));
    }

    public function avatar(string $jid): AvatarResponse
    {
        return $this->avatarHydrator->hydrate($this->get('/user/avatar', ['jid' => InputValidator::jid($jid)]));
    }

    public function privacy(): PrivacyResponse
    {
        return $this->privacyHydrator->hydrate($this->get('/user/my/privacy'));
    }

    public function myContacts(): MyContactsResponse
    {
        return $this->contactsHydrator->hydrate($this->get('/user/my/contacts'));
    }

    public function businessProfile(string $jid): BusinessProfileResponse
    {
        return $this->businessProfileHydrator->hydrate($this->get('/user/business-profile', ['jid' => InputValidator::jid($jid)]));
    }

    public function check(string $phone): UserCheckResponse
    {
        return $this->userCheckHydrator->hydrate($this->get('/user/check', ['phone' => InputValidator::phone($phone)]));
    }
}
