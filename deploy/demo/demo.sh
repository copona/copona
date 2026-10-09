#!/usr/bin/env bash
# Public demo store helper. Run from anywhere; see README.md in this folder.
#
#   ./demo.sh install    first start: build, install the store, take a snapshot
#   ./demo.sh reset      restore the database from the snapshot (run hourly from cron)
#   ./demo.sh snapshot   save the current database as the new clean state
set -euo pipefail

cd "$(dirname "$0")"

compose() { docker compose -f docker-compose.yml "$@"; }

[ -f demo.env ] || { echo "demo.env is missing: cp demo.env.example demo.env and fill it in" >&2; exit 1; }
set -a; . ./demo.env; set +a

db() { compose exec -T db mariadb -uroot -p"$MARIADB_ROOT_PASSWORD" "$@"; }

wait_for_db() {
    for _ in $(seq 1 60); do
        db -e 'SELECT 1' >/dev/null 2>&1 && return 0
        sleep 1
    done
    echo "MariaDB did not come up" >&2
    exit 1
}

snapshot() {
    compose exec -T db mariadb-dump -uroot -p"$MARIADB_ROOT_PASSWORD" --single-transaction --skip-lock-tables copona > snapshot.sql.tmp
    mv snapshot.sql.tmp snapshot.sql
    echo "Snapshot saved to $(pwd)/snapshot.sql"
}

reset() {
    [ -f snapshot.sql ] || { echo "No snapshot.sql yet: run ./demo.sh install or ./demo.sh snapshot" >&2; exit 1; }
    db -e 'DROP DATABASE IF EXISTS copona; CREATE DATABASE copona CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;'
    db copona < snapshot.sql
    # Cached pages/settings and visitor uploads belong to the old state.
    compose exec -T web sh -c 'rm -rf /app/storage/private/cache/* /app/storage/private/upload/* /app/storage/private/download/*'
    echo "Demo reset at $(date -u +%FT%TZ)"
}

install() {
    compose up -d --build
    wait_for_db
    compose exec -T -w /app web composer install --no-interaction --no-dev --optimize-autoloader
    compose exec -T -u application web php /app/copona install --no-interaction

    # Make sure PHP sees demo mode even if the web server doesn't pass the
    # container environment through to PHP.
    if ! grep -q '^DEMO_MODE=' ../../.env; then
        {
            echo
            echo 'DEMO_MODE=true'
            echo "DEMO_ADMIN_USERNAME=${DEMO_ADMIN_USERNAME:-}"
            echo "DEMO_ADMIN_PASSWORD=${DEMO_ADMIN_PASSWORD:-}"
            echo "DEMO_RESET_INTERVAL=\"${DEMO_RESET_INTERVAL:-every hour}\""
        } >> ../../.env
    fi

    # Visitors arrive over HTTPS through Cloudflare.
    db copona -e "UPDATE cp_setting SET value = '1' WHERE \`key\` = 'config_secure'"

    snapshot
    echo "Demo store installed. Add the hourly reset to cron, see README.md."
}

case "${1:-}" in
    install)  install ;;
    reset)    reset ;;
    snapshot) snapshot ;;
    *) echo "Usage: $0 install|reset|snapshot" >&2; exit 1 ;;
esac
