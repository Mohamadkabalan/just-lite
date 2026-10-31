# CI database fixture

`ci-database.sql` is the database GitHub Actions runs the tests against (see
`.github/workflows/ci-cd.yml`). The site's content tables were created through
Voyager, so there are no migrations to build them from; the fixture holds the
real schema plus the public content and the Voyager configuration (BREADs,
menus, roles, permissions, settings).

**This repository is public. Never put personal data in the fixture.**
The tables below are dumped as schema only:

`users`, `user_roles`, `contacts`, `appointments`, `password_resets`,
`personal_access_tokens`, `failed_jobs`

and the `site.google_analytics_tracking_id` setting is left out.

CI loads the fixture, then runs `php artisan migrate`, so new migrations are
tested against the production schema without regenerating the fixture.

## Regenerating

From a checkout whose `.env.testing` points at the **staging** database
(never production), with `DBH`, `DBP`, `DBU`, `DBN` and `MYSQL_PWD` exported
from it:

```bash
PERSONAL="users user_roles contacts appointments password_resets personal_access_tokens failed_jobs"
OPTS=(--host="$DBH" --port="$DBP" --user="$DBU" --single-transaction --skip-comments
      --skip-dump-date --no-tablespaces --skip-triggers --default-character-set=utf8mb4
      --skip-extended-insert)
IGN=(); for t in $PERSONAL settings; do IGN+=(--ignore-table="$DBN.$t"); done
{
  mysqldump "${OPTS[@]}" --no-data "$DBN" $PERSONAL
  mysqldump "${OPTS[@]}" "${IGN[@]}" "$DBN"
  mysqldump "${OPTS[@]}" --where="\`key\` <> 'site.google_analytics_tracking_id'" "$DBN" settings
} > tests/fixtures/ci-database.sql
```

Then check it before committing:

```bash
grep -ciE 'INSERT INTO `(users|user_roles|contacts|appointments|password_resets|personal_access_tokens|failed_jobs)`' tests/fixtures/ci-database.sql  # must be 0
grep -oiE '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}' tests/fixtures/ci-database.sql  # must print nothing
```
