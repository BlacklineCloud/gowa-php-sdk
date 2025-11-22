# Release & Maintenance Policy

- **Semantic Versioning**: MAJOR for breaking API changes, MINOR for new endpoints/features, PATCH for fixes.
- **PHP Support**: 8.2, 8.3, 8.4 (CI matrix). Drop only in MAJOR.
- **Upstream Alignment**: Track upstream OpenAPI/webhook docs. If spec drift is detected (see `bin/diff-openapi`), release a new MINOR if compatible, MAJOR if breaking.
- **Release Workflow**:
  1. Ensure CI green (lint/stan/psalm/tests/coverage).
  2. Update CHANGELOG and bump version in `composer.json`.
  3. Tag release; GitHub Actions publish release notes (Release Drafter) and push to Packagist.
- **Deprecations**: Mark deprecated APIs with PHPDoc `@deprecated` and document removal timeline. Remove only in next MAJOR.
- **Support Window**: Latest MAJOR supported; previous MAJOR receives security fixes only.
