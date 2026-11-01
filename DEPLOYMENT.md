# Deployment

The live site is a git checkout of `master` in
`~/domains/just-lite.com/public_html` on the Hostinger server.

## Pipeline

`.github/workflows/ci-cd.yml` runs on every push:

1. **Tests and static analysis**: `composer validate`, `composer audit`,
   migrations, Larastan and the PHPUnit suite on PHP 8.4. The database is
   MariaDB 11.8 loaded from `tests/fixtures/ci-database.sql` (see the README
   there).
2. **Vite build**: `npm ci && npm run build`.
3. **Deploy to production** (only on `master`, only when 1 and 2 pass): SSH
   to the server and run `scripts/deploy.sh deploy <sha>`, then check from
   outside that `/` and `/admin/login` return 200.

`scripts/deploy.sh` enters maintenance mode, fast-forwards to the commit,
runs `composer install --no-dev`, backs the database up to
`~/db-backups/just-lite-deploy-*.sql.gz` (newest 20 kept), runs
`php artisan migrate --force`, caches config and views, leaves maintenance
mode and checks the site. If any step fails, it resets the code and `vendor/`
to the previous commit and brings the site back up. It does **not** reverse
migrations; the log names the backup taken just before them.

Each deploy is logged to `storage/logs/deploy.log` on the server and in the
GitHub Actions run.

## One-time setup

1. The server has a deploy key in `~/.ssh/authorized_keys` that can only run
   the deploy script:

   ```
   command="/bin/bash /home/<user>/domains/just-lite.com/public_html/scripts/deploy.sh",no-agent-forwarding,no-port-forwarding,no-pty,no-user-rc,no-X11-forwarding ssh-ed25519 AAAA... github-actions-deploy@just-lite
   ```

2. In GitHub, *Settings → Secrets and variables → Actions → New repository
   secret*:

   | Secret            | Value                                         |
   | ----------------- | --------------------------------------------- |
   | `DEPLOY_SSH_KEY`  | the private half of that key (whole file)     |
   | `DEPLOY_SSH_USER` | the Hostinger SSH user (`u…`)                 |

   Until both are set, the deploy job skips itself with a warning.

   The deploy step rebuilds the key, so line endings and wrapping lost while
   pasting do not matter, and prints its fingerprint. It must match
   `ssh-keygen -lf ~/.ssh/just-lite-github-deploy.pub` on the server. If the
   step says the key is invalid, paste the file again in full.

3. Optional: in *Settings → Environments → production*, add yourself as a
   required reviewer to approve each deploy by hand.

## Day to day

- Deploy: merge or push to `master`. To redeploy without a new commit, run
  the workflow by hand (*Actions → CI/CD → Run workflow* on `master`).
- See what is live: `bash scripts/deploy.sh check` on the server.
- Deploy by hand on the server: `bash scripts/deploy.sh`.
- Undo a release: `git revert` the commit on `master` and push. If a migration
  must be undone too, restore the backup:
  `gunzip -c ~/db-backups/just-lite-deploy-<time>.sql.gz | mysql -u <user> -p <database>`.

## Rules for the server

- **Do not edit tracked files on the server.** The deploy refuses to run over
  local edits (`git status` shows them). Commit changes to the repository.
- **The web PHP version is set in the repository's root `.htaccess`**
  (`ea-php84`). If hPanel's PHP selector rewrites that file, commit the change
  or revert it, or the next deploy will refuse to run.
- **Config is cached.** After editing `.env` on the server, run
  `/opt/alt/php84/usr/bin/php artisan config:cache`.
- The CLI `php` on the server is 8.4; Laravel 13 needs 8.3 or newer.
- **Uploaded images live in `public/storage`**, the folder served at
  `/storage`. The `public` disk (Voyager uploads, `images:optimize`) writes
  there directly, because Hostinger disables PHP's `symlink()` and
  `php artisan storage:link` can't work. Don't run `storage:link`, and don't
  copy images into `storage/app/public`: nothing there is on the site.
  (`storage/app/public` on the server is an old copy from before
  2026-11-01; it can be deleted once `public/storage` is backed up.)

## Server settings (`.env`)

The server's `.env` is not in git. These values matter for a live site; edit
the file in hPanel's file manager or over SSH, then run
`/opt/alt/php84/usr/bin/php artisan config:cache`.

| Setting | Value | Why |
| --- | --- | --- |
| `APP_ENV` | `production` | Production behaviour (no dev tools, safe defaults) |
| `APP_DEBUG` | `false` | With `true`, any error shows visitors the code, queries and settings |
| `APP_URL` | `https://just-lite.com` | Links in emails and absolute URLs |
| `SESSION_SECURE_COOKIE` | `true` | Session cookie only over HTTPS |
| `LOG_LEVEL` | `warning` | Logs are daily files (14 kept); `debug` fills them quickly |
| `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME` | Hostinger email account (hPanel → Emails → the account → *Connect apps & devices* shows the SMTP host, port and encryption) | Needed for the emails below |
| `NOTIFY_EMAIL` | optional, default `Layla.G.Hanna@hotmail.com` | Receives an email for every booking and contact message |
| `ERROR_EMAIL` | the developer's address (optional) | Receives a short report when the site hits an error (same error at most once an hour) |
| `GTM_ID` | optional, default `GTM-MNGKCWV` | Google Tag Manager, loaded only after a visitor accepts analytics cookies |

If mail is not configured, bookings and messages are still saved (and shown
in the admin); the failed email is logged.

## Scheduled tasks (cron)

`app/Console/Kernel.php` schedules three daily tasks. They only run if the
server calls Laravel's scheduler every minute. In hPanel → Advanced → *Cron
Jobs*, add (with your user in the path):

```
* * * * * cd /home/<user>/domains/just-lite.com/public_html && /opt/alt/php84/usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

| Time | Task | What it does |
| --- | --- | --- |
| 02:30 | `db:backup --prefix=just-lite-daily --keep=14` | Database dump to `~/db-backups` (newest 14 kept) |
| 03:00 | `model:prune` | Deletes contact messages and bookings older than 24 months (`config/site.php`, stated in the privacy policy) |
| 03:30 | `images:optimize` | WebP copies of newly uploaded images (deploys also run it) |

**Before adding the cron job:** the first clean-up deletes every message and
booking from before the retention period (on the 2026-10-31 copy: 29 of 59
bookings and 1 message). Export them first in phpMyAdmin if you want to keep
them. Check what will go with
`php artisan model:prune --model="App\Contact" --model="App\Appointment" --pretend`.

Backups on the same server don't survive losing the account: copy
`~/db-backups` and `public/storage` (all uploaded images) somewhere else
regularly, or check that Hostinger's own backups cover them.

## Checks after a deploy

- Uploaded images: `public/storage/services/February2022`,
  `weekly-diet-plans/February2022`, `testimonials/February2022` and
  `users/default.png` were missing from the 2026-10-31 copy. If they are
  missing on the server too, re-upload those images in the admin.
- `storage/logs/laravel-<date>.log`: look for `CSP (report-only) would
  block` lines (below) and errors.

## Security headers

`app/Http/Middleware/SecurityHeaders.php` sends `X-Frame-Options`,
`X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`, HSTS (over
HTTPS) and, on public pages, a **report-only** Content-Security-Policy.
Browsers report what that policy would block to `/api/csp-report`, which logs
each distinct case once a day. When the log has shown no unexpected reports
for a few weeks (the GTM container can load other vendors), change the header
name `Content-Security-Policy-Report-Only` to `Content-Security-Policy` to
enforce it.

## Front-end assets

The theme's CSS and JS are plain files in `public/css` and `public/js`, loaded
with `asset_versioned()` (`?v=<file time>`), and `public/.htaccess` lets
browsers cache them for a year. They are not bundled with Vite: the server
deploys from git and has no Node.js, so a Vite build would mean committing
`public/build` or shipping CI-built files to the server. With gzip, HTTP/2
and caching the gain would be small. `npm run build` in CI only checks that
the Vite setup still builds.

`public/css/*.scss` are the theme's original sources. Nothing compiles them,
and they had already drifted from the `.css` files; edit the `.css` files.
