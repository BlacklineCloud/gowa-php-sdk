<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Client;

use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Contracts\Http\HttpTransportInterface;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\DevicesResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GenericResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\LoginResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\LoginWithCodeResponse;
use BlacklineCloud\SDK\GowaPHP\Http\ApiClient;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\DevicesResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GenericResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\LoginResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\LoginWithCodeResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Support\InputValidator;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class AppClient extends ApiClient
{
    public function __construct(
        ClientConfig $config,
        HttpTransportInterface $transport,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        private readonly LoginResponseHydrator $loginHydrator,
        private readonly LoginWithCodeResponseHydrator $loginCodeHydrator,
        private readonly DevicesResponseHydrator $devicesHydrator,
        private readonly GenericResponseHydrator $genericHydrator,
    ) {
        parent::__construct($config, $transport, $requestFactory, $streamFactory);
    }

    public function login(): LoginResponse
    {
        return $this->loginHydrator->hydrate($this->get('/app/login'));
    }

    public function loginWithCode(string $phoneNumber): LoginWithCodeResponse
    {
        return $this->loginCodeHydrator->hydrate($this->get('/app/login-with-code', ['phone' => InputValidator::phone($phoneNumber)]));
    }

    public function logout(): GenericResponse
    {
        return $this->genericHydrator->hydrate($this->get('/app/logout'));
    }

    public function reconnect(): GenericResponse
    {
        return $this->genericHydrator->hydrate($this->get('/app/reconnect'));
    }

    public function devices(): DevicesResponse
    {
        return $this->devicesHydrator->hydrate($this->get('/app/devices'));
    }
}
