#!/usr/bin/env bash
# Host bootstrap for Cursor Cloud / fuse-overlayfs desktops.
#
# Called by ./cc before `up` and `check` when the Docker storage driver is
# fuse-overlayfs. Does not start containers — cc handles that via compose.
#
# Usage:
#   ./scripts/cloud-prepare.sh           # docker, hosts, env, vendor, data dir
#   ./scripts/cloud-prepare.sh --post-up # after up: wait MySQL, key, fresh seed, assets

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

HOSTNAME="cardinalconstruct.test"

POST_UP=false
if [[ "${1:-}" == "--post-up" ]]; then
    POST_UP=true
fi

RED='\033[0;31m'
GREEN='\033[0;32m'
CYAN='\033[0;36m'
YELLOW='\033[1;33m'
NC='\033[0m'

log() { echo -e "${CYAN}>>>${NC} $*"; }
ok() { echo -e "${GREEN}✓${NC} $*"; }
warn() { echo -e "${YELLOW}!${NC} $*"; }

# shellcheck source=cloud-ensure-docker.sh
source "$(dirname "${BASH_SOURCE[0]}")/cloud-ensure-docker.sh"

# Match ./cc's compose invocation when fuse-cloud is active.
compose() {
    local files="${COMPOSE_FILE:-docker-compose.yml:compose.cloud.yaml}"
    COMPOSE_FILE="$files" docker_cmd compose "$@"
}

ensure_hosts() {
    if grep -qE '[[:space:]]cardinalconstruct\.test([[:space:]]|$)' /etc/hosts 2>/dev/null; then
        ok "${HOSTNAME} is in /etc/hosts"
        return
    fi

    log "Adding ${HOSTNAME} to /etc/hosts"
    echo "127.0.0.1 ${HOSTNAME}" | sudo tee -a /etc/hosts >/dev/null
    ok "${HOSTNAME} resolves to 127.0.0.1"
}

# Replace KEY=... in .env in place, or append if absent.
set_env_var() {
    local key="$1" val="$2"
    KEY="$key" VAL="$val" awk '
        BEGIN { key = ENVIRON["KEY"]; val = ENVIRON["VAL"]; done = 0 }
        !done && $0 ~ "^" key "=" { print key "=" val; done = 1; next }
        { print }
        END { if (!done) print key "=" val }
    ' .env >.env.tmp && mv .env.tmp .env
}

ensure_env() {
    if [[ -f .env ]]; then
        ok ".env exists"
        return
    fi

    log "Creating .env from .env.example (Docker service hostnames)"
    cp .env.example .env
    # .env.example still carries Homestead defaults — rewrite for Compose.
    set_env_var "APP_URL" "http://${HOSTNAME}"
    set_env_var "DB_HOST" "db"
    set_env_var "DB_DATABASE" "cardinalconstruct"
    set_env_var "DB_USERNAME" "cardinalconstruct"
    set_env_var "DB_PASSWORD" "password"
    set_env_var "REDIS_HOST" "redis"
    ok ".env created for Docker"
}

# Secrets from the Cursor Cloud Secrets panel arrive as host env vars; Compose
# reads the bind-mounted .env only. Mirror keys that are actually set.
CLOUD_SECRET_KEYS=(
    MAILGUN_DOMAIN
    MAILGUN_SECRET
    MAIL_USERNAME
    MAIL_PASSWORD
    STRIPE_KEY
    STRIPE_SECRET
    GOOGLE_CLIENT_ID
    GOOGLE_CLIENT_SECRET
    SES_KEY
    SES_SECRET
)

sync_env_secrets() {
    [[ -f .env ]] || return 0

    local key val
    local synced=()
    for key in "${CLOUD_SECRET_KEYS[@]}"; do
        val="${!key:-}"
        [[ -n "$val" ]] || continue
        grep -qxF "${key}=${val}" .env && continue
        set_env_var "$key" "$val"
        synced+=("$key")
    done

    if [[ ${#synced[@]} -gt 0 ]]; then
        ok "Synced secrets from environment into .env: ${synced[*]}"
    else
        ok "No environment secrets to sync into .env"
    fi
}

ensure_vendor() {
    if [[ -f vendor/autoload.php ]]; then
        ok "Composer vendor is installed"
        return
    fi

    log "Installing Composer dependencies (first run can take a few minutes)..."
    docker_cmd run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" \
        -w /var/www/html laravelsail/php84-composer:latest \
        composer install --ignore-platform-reqs --no-interaction
    ok "Composer vendor installed"
}

mysql_is_ready() {
    compose exec -T db mysqladmin ping -h localhost -uroot -proot --silent 2>/dev/null
}

ensure_app_key() {
    if grep -qE '^APP_KEY=base64:' .env 2>/dev/null; then
        ok "APP_KEY is set"
        return
    fi

    log "Generating APP_KEY"
    ./cc artisan key:generate
    ok "APP_KEY generated"
}

ensure_assets() {
    if [[ -f public/css/app.css && -f public/js/app.js ]]; then
        ok "Frontend assets exist under public/"
        return
    fi

    log "Building frontend assets (node:10 / Elixir)..."
    ./cc build
    ok "Frontend assets built"
}

ensure_fresh_database() {
    log "Seeding database (migrate:fresh --seed)..."

    local attempt
    for attempt in 1 2 3; do
        if ./cc fresh --yes; then
            ok "Database seeded"

            return
        fi

        warn "migrate:fresh attempt ${attempt} failed; waiting 5s..."
        sleep 5
    done

    echo -e "${RED}migrate:fresh failed after 3 attempts${NC}" >&2
    exit 1
}

if $POST_UP; then
    log "Waiting for MySQL to accept connections..."
    for _ in $(seq 1 60); do
        if mysql_is_ready; then
            ok "MySQL is ready"
            break
        fi
        sleep 2
    done

    if ! mysql_is_ready; then
        echo -e "${RED}MySQL did not become ready in time.${NC}" >&2
        exit 1
    fi

    ensure_app_key
    ensure_fresh_database
    ensure_assets
    exit 0
fi

ensure_docker
ensure_hosts
ensure_env
sync_env_secrets
ensure_vendor
mkdir -p .data/mysql
ok "Cloud host bootstrap complete"
