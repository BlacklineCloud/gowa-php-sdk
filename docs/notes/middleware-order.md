# Default Middleware Order

Recommended pipeline (outermost first):
1. `AuthMiddleware` — injects Basic auth and User-Agent.
2. `CorrelationIdMiddleware` — ensures `X-Correlation-ID` header for tracing.
3. `IdempotencyMiddleware` — sets `Idempotency-Key` header where absent.
4. `LoggingMiddleware` — records request/response with IDs.
5. `RetryMiddleware` — handles retryable failures and backoff.
6. Transport sender (`Psr18Transport::doSend`).

Rationale: authentication and identifiers must be present before logging; retries wrap the send so that logging reflects each attempt.
