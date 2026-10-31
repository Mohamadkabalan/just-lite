#!/usr/bin/env bash
#
# Deploys the master branch to the live checkout on the Hostinger server.
#
# The GitHub Actions "deploy" job runs it over SSH with a key that
# ~/.ssh/authorized_keys pins to this script (command="..."), so that key can
# do nothing else. It can also be run by hand on the server:
#
#   bash scripts/deploy.sh                # deploy the tip of origin/master
#   bash scripts/deploy.sh deploy <sha>   # deploy a specific commit of master
#   bash scripts/deploy.sh check          # show what is deployed; changes nothing
#
# Steps: fetch, maintenance mode, fast-forward, composer install, database
# backup, migrate, rebuild caches, leave maintenance mode, health check.
# If a step fails, the code and vendor/ go back to the previous commit and the
# site comes back up. Migrations are not reversed automatically: the log names
# the backup taken just before them.
#
# Settings (environment variables, all optional):
#   APP_DIR, DEPLOY_BRANCH, PHP_BIN, COMPOSER_BIN, BACKUP_DIR, KEEP_BACKUPS,
#   HEALTH_URLS (space separated; "none" skips the check)

APP_DIR="${APP_DIR:-$HOME/domains/just-lite.com/public_html}"
DEPLOY_BRANCH="${DEPLOY_BRANCH:-master}"
PHP_BIN="${PHP_BIN:-/opt/alt/php84/usr/bin/php}"
COMPOSER_BIN="${COMPOSER_BIN:-/usr/local/bin/composer}"
BACKUP_DIR="${BACKUP_DIR:-$HOME/db-backups}"
KEEP_BACKUPS="${KEEP_BACKUPS:-20}"
HEALTH_URLS="${HEALTH_URLS:-https://just-lite.com/ https://just-lite.com/admin/login}"
LOCK_FILE="${LOCK_FILE:-$HOME/.just-lite-deploy.lock}"

PREVIOUS=""
STEP=""
CODE_MOVED=0
MIGRATED=0

log() { printf '[%s] %s\n' "$(date '+%Y-%m-%d %H:%M:%S')" "$*"; }
die() { log "ERROR: $*"; exit 1; }
step() { STEP="$*"; log "$*"; }
artisan() { "$PHP_BIN" artisan "$@" --no-interaction; }
composer_install() {
    "$PHP_BIN" "$COMPOSER_BIN" install --no-dev --no-interaction --no-progress \
        --prefer-dist --optimize-autoloader
}

rollback() {
    local status=$?
    trap - ERR
    set +e
    log "Deploy FAILED during: ${STEP} (exit ${status})."

    if [[ "$CODE_MOVED" == 1 ]]; then
        log "Rolling the code back to ${PREVIOUS}"
        git reset --hard --quiet "$PREVIOUS"
        composer_install || log "composer install for ${PREVIOUS} failed; vendor/ needs a manual fix"
        artisan optimize:clear
        artisan config:cache
        artisan view:cache
    fi

    if [[ "$MIGRATED" == 1 ]]; then
        log "Migrations ran (or started) before the failure. If the old code cannot use the new"
        log "schema, restore the newest just-lite-deploy-*.sql.gz in ${BACKUP_DIR}."
    fi

    artisan up || log "Could not leave maintenance mode; run: php artisan up"
    exit "$status"
}

health_check() {
    [[ "$HEALTH_URLS" == none ]] && return 0

    local url code body
    body=$(mktemp)
    for url in $HEALTH_URLS; do
        code=$(curl -s -o "$body" -w '%{http_code}' --max-time 30 --retry 2 --retry-delay 3 "$url" || true)
        log "Health check ${url} -> ${code}"
        [[ "$code" == 200 ]] || { rm -f "$body"; return 1; }
        # A 200 alone is not enough: with a wrong PHP handler in .htaccess the
        # web server can send PHP source as text. Expect a rendered HTML page.
        if grep -q '<?php' "$body" || ! grep -qi '<html' "$body"; then
            log "Health check ${url}: the response is not a rendered HTML page"
            rm -f "$body"
            return 1
        fi
    done
    rm -f "$body"
}

main() {
    set -Eeuo pipefail

    local request="${*:-${SSH_ORIGINAL_COMMAND:-}}"
    local action target=""

    case "$request" in
        "" | deploy) action=deploy ;;
        check) action=check ;;
        "deploy "*) action=deploy target="${request#deploy }" ;;
        *) die "Unsupported command: ${request}" ;;
    esac

    [[ -z "$target" || "$target" =~ ^[0-9a-f]{7,40}$ ]] || die "Not a commit SHA: ${target}"

    cd "$APP_DIR"

    if [[ "$action" == deploy ]]; then
        # Write everything to the log and stream the log to the caller. If the
        # SSH connection drops, only tail dies; the deploy keeps going.
        local log_file="$APP_DIR/storage/logs/deploy.log"
        mkdir -p "$(dirname "$log_file")"
        touch "$log_file"
        tail -c "+$(( $(stat -c %s "$log_file") + 1 ))" -F --pid=$$ "$log_file" 2>/dev/null &
        trap '' HUP PIPE
        exec >>"$log_file" 2>&1
    fi

    exec 9>"$LOCK_FILE"
    flock -n 9 || die "Another deploy is running."

    # Single-threaded: shared hosting caps threads per user.
    git -c pack.threads=1 -c index.threads=1 fetch --quiet origin "$DEPLOY_BRANCH"
    local tip
    tip=$(git rev-parse "origin/${DEPLOY_BRANCH}")
    target=$(git rev-parse --verify --quiet "${target:-$tip}^{commit}") || die "Unknown commit: ${target}"
    PREVIOUS=$(git rev-parse HEAD)

    if [[ "$action" == check ]]; then
        log "Deployed: $(git log -1 --format='%h %s' "$PREVIOUS")"
        log "origin/${DEPLOY_BRANCH}: $(git log -1 --format='%h %s' "$tip")"
        log "PHP: $("$PHP_BIN" -r 'echo PHP_VERSION;')"
        artisan --version || true
        artisan migrate:status --pending || true
        return 0
    fi

    git merge-base --is-ancestor "$target" "$tip" || die "${target} is not on origin/${DEPLOY_BRANCH}."

    if [[ "$PREVIOUS" == "$target" ]]; then
        log "${target} is already deployed; nothing to do."
        return 0
    fi

    if git merge-base --is-ancestor "$target" "$PREVIOUS"; then
        log "A newer commit (${PREVIOUS}) is already deployed; skipping ${target}."
        return 0
    fi

    git merge-base --is-ancestor "$PREVIOUS" "$target" \
        || die "The deployed commit ${PREVIOUS} is not an ancestor of ${target}; fix the server checkout by hand."

    [[ -z "$(git status --porcelain --untracked-files=no)" ]] \
        || die "Tracked files were edited on the server (see git status). Commit them to the repository or revert them, then deploy again."

    log "Deploying ${PREVIOUS:0:7} -> ${target:0:7} ($(git log -1 --format=%s "$target"))"
    trap rollback ERR

    step "Entering maintenance mode"
    artisan down --retry=60 --refresh=15 || log "Could not enter maintenance mode; continuing."

    step "Updating the code"
    git merge --ff-only --quiet "$target"
    CODE_MOVED=1

    step "Installing dependencies"
    composer_install
    artisan optimize:clear

    step "Backing up the database"
    artisan db:backup --path="$BACKUP_DIR" --keep="$KEEP_BACKUPS"

    step "Running migrations"
    MIGRATED=1
    artisan migrate --force

    # Routes are not cached: Voyager registers a route per BREAD from the
    # database, and a cached route list would hide BREADs added in the admin.
    step "Caching config and views"
    artisan config:cache
    artisan view:cache

    step "Leaving maintenance mode"
    artisan up

    step "Checking the site"
    health_check

    trap - ERR
    log "Deployed ${target:0:7}."
}

# Everything above is parsed before main runs, so the git merge rewriting this
# file mid-deploy cannot change what is executing.
main "$@"; exit $?
