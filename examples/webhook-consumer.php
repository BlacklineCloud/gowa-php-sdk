<?php

declare(strict_types=1);

use BlacklineCloud\SDK\GowaPHP\Serialization\Json;
use BlacklineCloud\SDK\GowaPHP\Webhook\WebhookEventHydrator;
use BlacklineCloud\SDK\GowaPHP\Webhook\WebhookVerifier;

require __DIR__ . '/../vendor/autoload.php';

$raw = file_get_contents('php://input');
$sig = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
$secret = $_ENV['GOWA_WEBHOOK_SECRET'] ?? 'secret';

$verifier = new WebhookVerifier($secret);
if (! $verifier->verify($raw, $sig)) {
    http_response_code(401);
    echo 'invalid signature';
    exit;
}

$event = (new WebhookEventHydrator())->hydrate(Json::decode($raw));

if ($event->message) {
    error_log('Received message: ' . $event->message->text);
}

echo 'ok';
