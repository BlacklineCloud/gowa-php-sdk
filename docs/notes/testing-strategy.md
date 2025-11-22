# Testing Strategy

- **Unit tests**: value objects, hydrators, clients, middleware, webhook verifier/hydrator (currently in `tests/`).
- **Contract tests** (TODO): golden payloads vs OpenAPI examples, ensuring request/response structures stay stable. Use fixture files under `tests/Fixtures`.
- **Integration tests** (TODO): mock HTTP server (e.g., `php-http/mock-client`) validating retries/idempotency headers and transport behavior.
- **Static analysis**: PHPStan (level max) and Psalm (level max) in CI. Fix warnings before merging.
- **Coverage**: target >= 90%. Enable Xdebug in CI and publish badge (TODO).

Run locally:
```bash
composer lint
composer stan
composer psalm
composer test
```
