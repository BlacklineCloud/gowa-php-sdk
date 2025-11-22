## Upstream drift detected

`bin/diff-openapi` reported a checksum mismatch against `.docs/official/openapi.yaml` and/or `.docs/official/webhook-payload.md`.

### Next steps
- Run `bin/sync-upstream` to fetch the latest upstream specs.
- Regenerate DTOs/hydrators if schema changes occurred.
- Update `upstream/manifest.json` and rerun `bin/diff-openapi` to confirm alignment.
- Add changelog entries and bump version if public surface changes.

_This issue was created automatically._
