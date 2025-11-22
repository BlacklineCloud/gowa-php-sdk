# Gowa PHP SDK

[![coverage >= 90%](https://img.shields.io/badge/coverage-%E2%89%A5%2090%25-blue)](#coverage)

Typed, PSR-compliant PHP SDK for [Go WhatsApp Web MultiDevice](https://github.com/aldinokemal/go-whatsapp-web-multidevice). Root namespace: `BlacklineCloud\SDK\GowaPHP`.

## Installation

```bash
composer require blacklinecloud/gowa-php-sdk
```

## Quick Start

```php
use BlacklineCloud\SDK\GowaPHP\Client\AppClient;
use BlacklineCloud\SDK\GowaPHP\Client\SendClient;
use BlacklineCloud\SDK\GowaPHP\Config\ClientConfigBuilder;
use BlacklineCloud\SDK\GowaPHP\Http\ClientFactory;
use BlacklineCloud\SDK\GowaPHP\Support\NativeUuidGenerator;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\HttpClient\CurlClient;
use Psr\Log\NullLogger;

$config = ClientConfigBuilder::fromArray([
    'base_uri' => 'http://localhost:3000',
    'username' => 'admin',
    'password' => 'admin',
]);

$factory = new ClientFactory(
    requestFactory: $psr17 = new Psr17Factory(),
    streamFactory: $psr17,
    psr18: new CurlClient($psr17),
    logger: new NullLogger(),
    uuid: new NativeUuidGenerator(),
);

$app  = $factory->createAppClient($config);
$send = $factory->createSendClient($config);

// Login (QR)
$app->login();

// Send text
$send->text('628123456789@s.whatsapp.net', 'Hello from PHP');
// Send chat presence
$send->chatPresence('628123456789@s.whatsapp.net', \BlacklineCloud\SDK\GowaPHP\Domain\Enum\PresenceState::Composing);
```

## Webhook Verification

See `docs/notes/webhook-verification.md` for signature verification, PSR-15 middleware example, and idempotency tips. In brief:

```php
$raw = file_get_contents('php://input');
$sig = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
$verifier = new \BlacklineCloud\SDK\GowaPHP\Webhook\WebhookVerifier('your-secret');
if (! $verifier->verify($raw, $sig)) {
    http_response_code(401);
    exit('invalid signature');
}
$event = (new \BlacklineCloud\SDK\GowaPHP\Webhook\WebhookEventHydrator())->hydrate(\BlacklineCloud\SDK\GowaPHP\Serialization\Json::decode($raw));
```

## Supported PHP

PHP 8.2+ (tested on 8.2/8.3/8.4).

## Contributing

- Follow SOLID/DRY/KISS/YAGNI and PSR standards.
- Use constructor injection; keep public APIs typed (no arrays in signatures).
- Run tests and static analysis: `composer lint && composer stan && composer psalm && composer test`.
- See `CONTRIBUTING.md` for the self-review checklist and upgrade workflow.

## Coverage

CI enforces >= 90% coverage via `bin/check-coverage` (see `composer coverage`). Enable Xdebug locally to avoid warnings.

### Run tests with Docker + Xdebug

```bash
docker compose -f docker-compose.dev.yml build
# Run full suite with coverage
docker compose -f docker-compose.dev.yml run --rm php composer test -- --coverage-clover=build/logs/clover.xml
docker compose -f docker-compose.dev.yml run --rm php composer coverage
```
