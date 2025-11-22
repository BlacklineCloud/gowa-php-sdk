# Webhook Verification & Handling

## Verify Signatures
- Webhooks include `X-Hub-Signature-256: sha256=<hex>` using the configured secret (default `secret`).
- Use `WebhookVerifier` with the raw body, not the parsed JSON.

```php
use BlacklineCloud\SDK\GowaPHP\Webhook\WebhookVerifier;

$raw = file_get_contents('php://input');
$sig = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
$verifier = new WebhookVerifier($_ENV['GOWA_WEBHOOK_SECRET'] ?? 'secret');

if (! $verifier->verify($raw, $sig)) {
    http_response_code(401);
    exit('invalid signature');
}
```

## Deserialize Events
- `WebhookEventHydrator` maps the official payloads to typed models (message, media, receipt, group participant, protocol actions).

```php
use BlacklineCloud\SDK\GowaPHP\Serialization\Json;
use BlacklineCloud\SDK\GowaPHP\Webhook\WebhookEventHydrator;

$payload = Json::decode($raw);
$event = (new WebhookEventHydrator())->hydrate($payload);

if ($event->receipt) {
    // handle receipts
} elseif ($event->groupParticipants) {
    // handle group joins/leaves
} elseif ($event->message) {
    // handle messages
}
```

## Idempotency Guidance
- Use stable IDs from payloads to de-duplicate:
  - Message events: `message.id`
  - Receipts: hash of `chat_id` + `ids` + `receipt_type`
  - Group participants: `chat_id` + `type` + `jids`
- Store processed IDs in your persistence layer to avoid double-processing (the sender retries with backoff).

## PSR-15 Middleware Example (Slim/Mezzio style)
```php
use BlacklineCloud\SDK\GowaPHP\Webhook\WebhookVerifier;
use BlacklineCloud\SDK\GowaPHP\Webhook\WebhookEventHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Json;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Response;

final class WebhookMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly string $secret)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $raw = (string) $request->getBody();
        $sig = $request->getHeaderLine('X-Hub-Signature-256');
        $verifier = new WebhookVerifier($this->secret);

        if (! $verifier->verify($raw, $sig)) {
            return new Response(401);
        }

        $event = (new WebhookEventHydrator())->hydrate(Json::decode($raw));
        // Attach to request attributes for downstream handlers
        return $handler->handle($request->withAttribute('gowa_webhook_event', $event));
    }
}
```
