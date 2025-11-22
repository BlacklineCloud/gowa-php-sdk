# Gowa PHP SDK — Development Plan

> Root namespace: `BlacklineCloud\SDK\GowaPHP`
> Upstream reference: https://github.com/aldinokemal/go-whatsapp-web-multidevice (openapi 6.12.0, webhook-payload.md)

## 0. Discovery (must be completed before coding)
- [x] Re-read `.docs/official/readme.md` and note runtime modes (REST/MCP), auth (Basic), media constraints
- [x] Extract endpoint list & grouping from `.docs/official/openapi.yaml` (app, user, send, message, chat, group, newsletter)
- [x] Catalogue webhook event schemas from `.docs/official/webhook-payload.md` (message/receipt/group/media/special flags)
- [x] Capture upstream versions & checksums (openapi.yaml + webhook-payload.md) into `upstream/manifest.json`

## 1. Repository Scaffolding & Quality Gates
- [x] `composer.json`: PHP >= 8.2, PSR-4 autoload `BlacklineCloud\\SDK\\GowaPHP\\`
- [x] Require: `psr/http-client`, `psr/http-factory`, `psr/log`; dev: `phpstan/phpstan`, `vimeo/psalm`, `phpunit/phpunit` (or `pestphp/pest`), `friendsofphp/php-cs-fixer`
- [x] Tooling configs: `.php-cs-fixer.php`, `phpstan.neon`, `psalm.xml`, `.editorconfig`
- [x] CI (GitHub Actions): lint, static analysis, tests, coverage upload; matrix PHP 8.2/8.3/8.4
- [x] Repo meta: `LICENSE` (MIT), `CODE_OF_CONDUCT.md`, `CONTRIBUTING.md`, `SECURITY.md`, `CHANGELOG.md`
- [x] GitHub templates: `.github/ISSUE_TEMPLATE/bug.yml`, `feature.yml`, `PULL_REQUEST_TEMPLATE.md`
- [x] Release automation: release-drafter or semantic-release workflow; Packagist auto-update hook

## 2. Core Architecture & Abstractions
- [x] Define `Contracts\Http\HttpTransportInterface` (PSR-7/18 based) and `Contracts\Http\MiddlewareInterface`
- [x] Implement default transport adapter (e.g., HTTPlug + discovery) with retry/backoff and idempotency token support
- [x] Introduce `ClientConfig` (immutable): baseUrl, auth, timeouts, serialization options, retry policy
- [x] Error model: rich domain exceptions hierarchy (transport, auth, validation, rate-limit, server)
- [x] Logging hooks (PSR-3) + correlation IDs

## 3. Domain Model & Serialization
- [x] Value Objects: `Jid`, `MessageId`, `Timestamp`, `PhoneNumber`, `MediaPath`, `WebhookSignature`
- [x] Enums: message ack status, receipt type, group action type, media mime types, presence states
- [x] Request/Response DTOs per OpenAPI schemas; use readonly properties, typed collections (framework in place; DTOs for Login/Send/Devices/Privacy/UserInfo/Avatar/BusinessProfile/GroupInfoFromLink/GroupParticipants/ManageParticipant/Chat list & messages/Chat actions/Newsletter/Generic/Contacts/Group list/Participant requests/Message actions/Webhook events)
- [x] Serializer layer: JSON (fail-fast), request builders, response hydrators; map unknown fields conservatively
- [x] Validation: input guards (Design by Contract) with descriptive exceptions
- [x] Self-review checklist applied to models & serializer

## 4. Service Clients (by API tag)
- [x] `AppClient`: login (QR/code), logout, reconnect, devices
- [x] `UserClient`: info, avatar, pushname, privacy, my groups/contacts/newsletters, business profile
- [x] `SendClient`: text, link, location, contact, presence/chat presence, image/audio/file/sticker/video/poll
- [x] `MessageClient`: revoke, delete, reaction, update, read, star/unstar
- [x] `ChatClient`: list chats, chat messages, labels, pin
- [x] `GroupClient`: info-from-link, create, list, participants add/remove/promote/demote/export, join via link, participant requests approve/reject, leave, photo, name, locked, announce, topic, invite link
- [x] `NewsletterClient`: list, unfollow (extension ready)
- [x] Consistent method signatures: strongly-typed inputs/outputs; participant lists now variadic
- [x] Unit tests + self-review per client (App/User/Send/Message/Chat/Group/Newsletter covered)

## 5. Webhook Support
- [x] `WebhookVerifier` implementing HMAC-SHA256 signature check, constant-time comparison
- [x] Event deserializer mapping payloads to domain events (message, receipt, group, media, special flags)
- [x] Idempotency support (event ID tracking) and duplicate handling guidance
- [x] Middleware example for common frameworks (PSR-15 adapter + Laravel/Symfony bridges) with docs
- [x] Self-review checklist for webhook layer

## 6. Configuration & Extensibility
- [x] Config builder from env/array with validation; sensible defaults (timeouts, base path)
- [x] Pluggable middlewares: auth (Basic), tracing/logging, retry, circuit breaker placeholder
- [x] File/media upload strategy abstraction to support local file path, stream, PSR-7 stream
- [x] Clock & UUID providers injected for testability

## 7. Update Tracking & Upstream Sync
- [x] Create `bin/sync-upstream` to fetch latest `openapi.yaml` and `webhook-payload.md`, store in `upstream/` with hashes
- [x] Create `bin/diff-openapi` to compare stored spec with generated DTO map; exit non-zero on drift
- [x] CI scheduled job (weekly) to run diff and open issue/PR when changes detected
- [x] Document upgrade workflow in `CONTRIBUTING.md` (regenerate DTOs, update changelog, bump version)

## 8. Testing Strategy
- [x] Unit tests: value objects, serializers, signature verification, error mapping, retry policy
- [x] Contract tests: golden files for request/response serialization vs OpenAPI examples (initial fixture for send text)
- [x] Integration tests: HTTP mock server (e.g., `php-http/mock-client`); ensure retry/idempotency behavior (initial retry test in place)
- [x] Static analysis gates: PHPStan lvl max, Psalm lvl 1; 100% type coverage goal
- [ ] Code coverage target >= 90%, track via CI badge

## 9. Documentation & DX
- [x] README: installation, quick start, minimal send example, webhook verification snippet, changelog link
- [x] API docs: generated from PHPDoc + examples; publish via GitHub Pages action (workflow placeholder added)
- [x] Usage recipes: login flows (QR/code), sending media, group admin, webhook handling, error handling patterns
- [x] Contribution guide: coding standards, commit convention (Conventional Commits), review checklist, test matrix
- [x] Example apps: CLI demo and minimal webhook consumer

## 10. Release & Maintenance
- [x] Semantic versioning policy; document supported upstream API versions
- [ ] Release workflow: tag -> changelog -> GitHub release -> Packagist sync
- [ ] Deprecation policy and compatibility notes (breaking change window)
- [x] Support matrix: PHP versions, HTTP clients
- [ ] Ongoing self-review: ensure SOLID, DRY, KISS, YAGNI, PSR compliance before each release

## Definition of Done
- [ ] All plan tasks completed
- [ ] Self-review checklist satisfied for every component
- [ ] CI green (lint, static analysis, tests, coverage)
- [ ] Docs updated (README + API + changelog)
