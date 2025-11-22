# Gowa PHP SDK

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
use BlacklineCloud\SDK\GowaPHP\Http\Psr18Transport;
use BlacklineCloud\SDK\GowaPHP\Http\Middleware\{AuthMiddleware,CorrelationIdMiddleware,IdempotencyMiddleware,LoggingMiddleware,RetryMiddleware};
use BlacklineCloud\SDK\GowaPHP\Support\{NativeUuidGenerator,SystemClock};
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\SendResponseHydrator;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\HttpClient\CurlClient;
use Psr\Log\NullLogger;

$config = ClientConfigBuilder::fromArray([
    'base_uri' => 'http://localhost:3000',
    'username' => 'admin',
    'password' => 'admin',
]);

$psr17 = new Psr17Factory();
$uuid = new NativeUuidGenerator();
$transport = new Psr18Transport(
    new CurlClient($psr17),
    $config,
    new NullLogger(),
    new AuthMiddleware($config),
    new CorrelationIdMiddleware($uuid),
    new IdempotencyMiddleware($uuid),
    new LoggingMiddleware(new NullLogger()),
    new RetryMiddleware($config),
);

$app = new AppClient($config, $transport, $psr17, $psr17, new \BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\LoginResponseHydrator(), new \BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\LoginWithCodeResponseHydrator(), new \BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\DevicesResponseHydrator(), new \BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GenericResponseHydrator());
$send = new SendClient($config, $transport, $psr17, $psr17, new SendResponseHydrator());

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
