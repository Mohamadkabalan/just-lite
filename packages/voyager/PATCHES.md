# Local Voyager package

This directory is the official **tcg/voyager v1.8.0** (the last upstream release;
upstream is abandoned) with the small patches listed below. It is installed
through the `path` repository in the root `composer.json`.

- Upstream: https://github.com/thedevdojo/voyager
- Base commit: `3b0c42d8cf966543ac89bc37017ebfd8260da0bc` (tag v1.8.0)
- Base zipball sha256: `2e09e68b6df5a71d3300ced2100c5b128683d10ac654cd85e019a05e75281b3c`
- The unmodified base is the git commit "Add official Voyager v1.8.0 as a local
  package (unmodified)"; `git diff <that commit> -- packages/voyager` shows every
  local change.

Version scheme: `1.8.0.N`, where N counts local patch releases.

## Patches

| Version | Change | Why |
|---|---|---|
| 1.8.0.1 | `illuminate/support` constraint `11.*` → `^11.0\|^12.0\|^13.0` | Run on Laravel 12/13; verified with the app's smoke tests on PHP 8.3/8.4 |
| 1.8.0.1 | `SchemaManager::getColumnIndexes()` returns a re-indexed list | Upstream bug from the DBAL removal: the BREAD builder 500s for tables such as `users` |
| 1.8.0.2 | `SchemaManager::listTableNames()` passes the connection's database to `getTables()` | Laravel 12+ returns tables from every schema the DB user can see; the BREAD and Database screens must only list this app's tables |
| 1.8.0.3 | New `Support\UploadGuard`; the media manager also requires the file extension to belong to an allowed MIME type and rejects names with a script extension anywhere (`x.php`, `x.php.jpg`, dotfiles); the `File` field type skips such files | CVE-2024-55417: the MIME check read the content, but files kept the client's extension, so an image/PHP polyglot named `x.php` was stored in the public disk and executed |
| 1.8.0.4 | `VoyagerCompassController::index()` always returns 404 and `MenuItemPolicy` always hides the Compass menu item | Compass (command runner, log viewer) has unfixed CVEs and was only blocked while `APP_ENV` was not `local`, so one wrong `.env` value gave admins remote code execution |

## Known upstream security advisories

These affect every official release up to 1.8.0 and have **no upstream fix**.
Because this package's version is now 1.8.0.x, `composer audit` no longer
matches them, so they are tracked here instead.

| CVE | Area | Status here |
|---|---|---|
| CVE-2025-32931 (critical) | Compass: admins can run OS commands via an artisan command | **Mitigated in 1.8.0.4** — Compass is disabled in every environment (404) |
| CVE-2024-55415 (high) | Compass: path traversal | **Mitigated in 1.8.0.4** — same as above |
| CVE-2024-55416 (low) | Compass: reflected XSS | **Mitigated in 1.8.0.4** — same as above |
| CVE-2024-55417 (medium) | Media manager upload: file-type check bypass (web shell) | **Fixed in 1.8.0.3** — see the patch table; the app also ships `storage/app/public/.htaccess`, which refuses to serve scripts from `/storage` |

Update this table when a fix lands.
